<?php
    require_once("../lib/library.php");
    session_start();
    if(isset($_SESSION['user_auth']))
    {
        redirect("user/account.php");
        exit();
    }
        

    if($_SERVER['REQUEST_METHOD'] === "GET")
    {
        $id_user = $_GET['id'];
        $token = $_GET['token'];

        if(!empty($id_user) && !empty($token))
        {
            require_once("../app/models/user.php");
            $restpwd = new User();
            $user = $restpwd->getUserById($id_user);
            if(!$user)
                redirect('../signin.php');
            else{
                if($user->token_confirmation === null && $user->enabled == 1 && $user->confirmed_at != null && $token == $user->token_reset)
                {
                    $origin = new DateTime($user->reset_at);
                    $target = new DateTime('now');
                    $origin->add(new DateInterval('PT30M0S'));//add 30 minute to reset_at
                    if($target > $origin)
                    {                 
                        session_start();
                        $arg_notifiction['theme'] = 'info';
                        $arg_notifiction['title'] = 'token invalid';
                        $arg_notifiction['message'] = 'Get a new link by trying forgot password.';
                        $_SESSION['Notification'] = $arg_notifiction;
                        redirect("../signin.php");
                        exit();
                    }
                }
                else
                {
                    redirect("../signin.php");
                    exit();
                }
            }
        }
        else
        {
            redirect("../signin.php");
            exit();
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $errors;
        $password = $_POST['password'];
        $confirm_password = $_POST['password-confirm'];

        if(empty($password) || empty($confirm_password) || $password != $confirm_password)
        {
            if($password != $confirm_password)
                $errors['confirm-password'] = "The password are not the same";
            else
                $errors['password'] = "password is requird";
        }
        else{
            require_once("../app/models/user.php");
            $user = new User();
            if($user->userResetPassword($password,$_GET['id']))
            {
                session_start();    
                $_SESSION['user_auth'] = $user->getUserById($_GET['id']);
                redirect('user/account.php');
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Contactes - Reset password</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
    <div class='simple-Modal'>
        <div class='my-modal'>
            <div class='head-modal'><h1>Reset Password</h1></div>
                <div class='body-modal'>

                    <form action='' method='POST'>
                        <div class='mb-3'>
                            <label for='Inputpassword' class='form-label'>New Password</label>
                            <input type='password' name='password' class='form-control <?php isset($errors['password']) ? setString('is-invalid') : false?>' id='Inputpassword' aria-describedby='emailHelp'>
                            <div class="invalid-feedback">
                                <?php isset($errors['password']) ? setString($errors['password']) : false ?>
                            </div>
                        </div>
                        <div class='mb-3'>
                            <label for='exampleInputPassword1' class='form-label'>Confirm Password</label>
                            <input type='password' name='password-confirm' class='form-control <?php isset($errors['confirm-password']) ? setString('is-invalid') : false ?>' id='exampleInputPassword1'>
                            <div class="invalid-feedback">
                                <?php isset($errors['confirm-password']) ? setString($errors['confirm-password']) : false?>
                            </div>
                        </div>
                        <div class="group-btn">
                            <button type='submit' class='btn btn-primary'>Reset Now</button>
                            <a class="btn btn-danger" href="../signin.php">Cancel</a>                           
                        </div>
                    </form>

                </div>
            </div>    
        </div>
    </div>
</body>
</html>