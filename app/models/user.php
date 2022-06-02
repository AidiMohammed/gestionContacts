<?php
    require_once('data_provider.php');

    class User extends DataProvider
    {
        public function addUser($userName,$email,$pwd)
        {
            $db = $this->connect();
            if($db == null)
                return false;
                
            
            $token = str_random(60);
            $sql = "INSERT INTO users (user_name,email,enabled,password,token_confirmation) VALUES (:user_name,:email,:enabled,:password,:token_confirmation)";
            $stm =  $db->prepare($sql);
            $stm->execute([
                ":user_name" => $userName,
                ":email" => strtolower($email),
                ":password" => $pwd,
                ":enabled" => 0,
                ":token_confirmation" => $token
            ]);
            $user_id = $db->lastInsertId();

            $sql = "INSERT INTO users_profile (user_id)  VALUE (:user_id)";
            $stm = $db->prepare($sql);
            $stm->execute([":user_id" => $user_id]);

            mail($email,"Confirmation de votre compte","Afin de valider votre compte merci de cliquer sur ce line :\n\n http://localhost/Contactes/pages/confirmation.php?id=$user_id&token=$token");
            $stm = null;
            $db = null;

            return true;
        }

        public function confirmdNewUser($id,$token_confirmation)
        {
            $db = $this->connect();
            if($db == null)return false;

            $user = $this->getUserById($id);

            if($user && $user->token_confirmation == $token_confirmation)
            {
                $sql = "UPDATE users SET token_confirmation = NULL , confirmed_at = NOW() , enabled = 1 WHERE id = :id";
                $stm = $db->prepare($sql);
                $stm->execute([":id" => $id]);
    
                $db = null;
                $stm = null;

                session_start();
                $_SESSION['user_auth'] = $user;
                redirect('user/account.php');
            }
            else
                redirect('../signin.php');
        }

        public function checkUsernameEmailIfExist($email,$username)
        {
            //cétte méthode doit etre modifié avec une seul requete sql
            $errors = [];
            $db = $this->connect();
            if($db == null)return false;
            
            $sql = "SELECT * FROM users WHERE user_name = :username";
            $stm = $db->prepare($sql);
            $stm->execute([':username' => $username]);
            $user = $stm->fetch(PDO::FETCH_OBJ);

            if($user)
                $errors['username'] = "This username is already in use please choose another one !!";
            
            $sql = "SELECT * FROM users WHERE email = :email";
            $stm = $db->prepare($sql);
            $stm->execute([':email' => $email]);
            $user = $stm->fetch(PDO::FETCH_OBJ);

            if($user)
               $errors['email'] = "This email is already in use. please enter a new one !!";

            $db = null;
            $stm = null;

            return $errors;
        }
        public function checkUserNameIfExist($username)
        {
            $errors = [];
            $db = $this->connect();
            if($db == null)return false;
            
            $sql = "SELECT * FROM users WHERE user_name = :username";
            $stm = $db->prepare($sql);
            $stm->execute([':username' => $username]);
            $user = $stm->fetch(PDO::FETCH_OBJ);

            if($user)
                $errors['username'] = "This username is already in use please choose another one !!";
            return $errors;
        }
        public function checkEmailIfExist($email)
        {
            $errors = [];
            $db = $this->connect();
            if($db == null)return false;
            
            $sql = "SELECT * FROM users WHERE email = :email";
            $stm = $db->prepare($sql);
            $stm->execute([':email' => $email]);
            $user = $stm->fetch(PDO::FETCH_OBJ);

            if($user)
                $errors['email'] = "This email is already in use. please enter a new one !!";
            return $errors;
        }
        public function getUserById($id)
        {
            $db = $this->connect();
            if($db == null)return false;
            // /!\ ont doit récupérer que les champs 'user_name' 'email' 'id'
            $req = "SELECT * FROM users WHERE id = ?";
            $stm = $db->prepare($req);
            $stm->execute([$id]);
            $user = $stm->fetch(PDO::FETCH_OBJ);
            return $user;
        }
        public function getUserByUserName($userName)
        {
            
            $db = $this->connect();
            if($db == null)return false;

            $sql = "SELECT * FROM users WHERE user_name = :username";
            $stm = $db->prepare($sql);
            $stm->execute([':username' => $userName]);
            $user = $stm->fetch(PDO::FETCH_OBJ);

            if(!$user)
            {
                $db = null;
                $stm = null;
                return false;
            }
            else{
                $db = null;
                $stm = null;
                return $user;                
            }

        }
        public function getUserByEmail($email)
        {
            $db = $this->connect();
            if($db == null)return false;

            $sql = "SELECT * FROM users WHERE email = :email";
            $stm = $db->prepare($sql);
            $stm->execute([':email' => $email]);
            $user = $stm->fetch(PDO::FETCH_OBJ);

            if(!$user)
            {
                $db = null;
                $stm = null;
                return false;
            }
            else{
                $db = null;
                $stm = null;
                return $user;                
            }
        }
        public function getAllUsers()
        {
            $db = $this->connect();
            if($db == null)return false;

            $qurey = $db->query("SELECT * FROM users");
            $users = $qurey->fetchAll(PDO::FETCH_OBJ);

            $db = null;
            $qurey = null;

            return $users;
        }
        public function userLogin($userNameOrEmail,$pwd)
        {
            // /!\ l'utilisateur il peut se connecter soit par 'user name' ou 'email'
            $user = $this->getUserByUserName($userNameOrEmail);
            if($user)
            {
                if(password_verify($pwd,$user->password) && $user->token_confirmation == null && $user->confirmed_at != null && $user->enabled == 1)
                    return $user;
                else
                {
                    if(password_verify($pwd,$user->password) && $user->confirmed_at == null && $user->token_confirmation != null)
                        return 'user not confirmed';
                    if(password_verify($pwd,$user->password) && $user->token_confirmation == null && $user->confirmed_at != null && $user->enabled == 0)
                        return 'user not enabled';
                    if(!password_verify($pwd,$user->password))
                        return false;
                }
            }
            else
                return false; 
        }
        public function userForgottPassword($email)
        {
            $user = $this->getUserByEmail($email);
            if($user && $user->token_confirmation == null && $user->confirmed_at != null && $user->enabled == 1)
            {
                $db = $this->connect();
                if($db==null)return false;

                $token_reset = str_random(60);
                $sql = "UPDATE users SET token_reset = ? , reset_at = NOW() WHERE id = ?";
                $stm = $db->prepare($sql);
                $stm->execute([$token_reset,$user->id]);

                $dateTime = new DateTime();
                $message = "Bonjour $user->user_name ,\n En date du {$dateTime->format('d-m-Y')} à {$dateTime->format('H:i:s')} (heure marocaine), nous avons reçu une demande de réinitialisation de votre mot de passe Save Contacts.\nCliquez sur ce line : http://localhost/Contactes/pages/resetpassword.php?id=$user->id&token=$token_reset\n pour changer votre mot de passe\nPour des raisons de sécurité, ce lien expire au bout de 30 minutes. Passé ce délai, il faut refaire la demande de réinitialisation.\nSi vous n’avez pas initié cette demande, ne tenez pas compte de cet e-mail.\nMerci,";
                mail($email,"Réinitialisation de votre mot de passe",$message);
                $stm = null;
                $db = null;

                return true;
            }else
                return false;
        }
        public function notFirstUse($id)
        {
            $db = $this->connect();
            if($db === null)return false;

            $sql = "UPDATE users SET first_use = 0 WHERE id = ?";
            $db->prepare($sql)->execute([$id]);

            $db = null;

        }
        public function userUpdateUserName($userName,$user_id)
        {
            $db = $this->connect();
            if($db == null)return false;

            $sql = "UPDATE users SET user_name = ? WHERE id = ?";
            $stm = $db->prepare($sql);
            $result = $stm->execute([$userName,$user_id]);

            $db = null;
            $stm = null;

            return $result;

        }
        public function userUpdateEmail($email,$user_id)
        {
            $db = $this->connect();
            if($db == null)return false;

            $sql = "UPDATE users SET email = ? WHERE id = ?";
            $stm = $db->prepare($sql);
            $result = $stm->execute([$email,$user_id]);

            $db = null;
            $stm = null;

            return $result;
        }
        public function userResetPassword($password,$user_id)
        {
            $db = $this->connect();
            if($db === null)return false;
            
            $password_hash = password_hash($password,PASSWORD_BCRYPT,['cost'=> 12]);
            $sql = "UPDATE users SET token_reset = NUll,reset_at = NULL, password = ? WHERE id = ?";
            if($db->prepare($sql)->execute([$password_hash,$user_id]))
            {
                $db = null;
                return true;
            }else
            {
                $db=null;
                return false;
            }
        }
    }
?>