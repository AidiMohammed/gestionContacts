<?php

    require_once('./lib/library.php');
    session_start();

    if(isset($_SESSION['user_auth']))
        redirect('./pages/user/account.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        require_once('./app/models/user.php');
        require_once('./lib/library.php');
        //********** validation inputs *************//
        $errors = [];
        $userName = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password-confirm'];

        //**************** /!\ ajouter une vérification avec des expression réguliére au nom d'utilisateur || add a check with regular expressions to user name /!\***************
        if(empty($userName) || strlen($userName) < 5)
        {
            if(!empty($userName))
                 $errors['username'] = 'A user name must contain at least 5 characters !!';                   
            else
                $errors['username'] = 'User names is required !!';       
        }
        if(empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            if(!empty($email))
                $errors['email'] = "Email address is invalid !!";                
            else
                $errors['email'] = "Email is required !!";
        }
        //**************** /!\ ajouter une vérification avec des expression réguliére au mot de passe || add a check with regular expressions to password /!\***************
        if(empty($password) || $password != $password_confirm)
        {
            if(!empty($password))
                $errors['password'] = "The passwords are not the same !!";
            else
                $errors['password'] = "password is requird !!";
        }
        
        if(empty($errors))
        {
            //*************** /!\ vérification si le nom d'utilisateur et email existe dans la basse de donnée ||check if userame & email exist in database*/
            $checkUsernameEmail = new User();
            $errors = $checkUsernameEmail->checkUsernameEmailIfExist($email,$userName);
            
            if(empty($errors))
            {
                $options = ['cost' => 12];
                $password_hash = password_hash($password,PASSWORD_BCRYPT,$options);
                $newUser = new User();
                if($newUser->addUser($userName,$email,$password_hash))
                {
                    session_start();
                    $arg_notifiction['theme'] = 'success';
                    $arg_notifiction['title'] = 'Verification email sent';
                    $arg_notifiction['message'] = "A validation email has been sent to your account <span style='font-weight: 600;letter-spacing: 0.6px;color: white;'>$email</span>.";
                    $_SESSION['Notification'] = $arg_notifiction;
                    redirect("signin.php");
                    exit();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save contacts - signup</title>

    <link rel="stylesheet" href="./assets/css/signup.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<!-- icons -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>

<main class="form-signup">

    <form action="" method="POST">
        <div class="div-img">
            <img class="mb-4" src="./assets/img/undraw_welcome_re_h3d9.svg" alt="image login" width="300" height="250">    
        </div>

        <div class="mb-3">
            <label for="InputUserName" class="form-label">User name</label>
            <input type="text" value="<?php isset($userName) ? setString($userName) : false ?>"  name="username" class="form-control <?php (isset($errors['username']) || !empty($errors['username'])) ? setString("is-invalid") : false ?>" id="InputUserName" aria-describedby="usernameHelp">
            <div class="invalid-feedback">
                <?php isset($errors['username']) ? setString($errors['username']) : false?>
            </div>
        </div>

        <div class="mb-3">
            <label for="exampleInputEmail" class="form-label">Email</label>
            <input type="email" name="email" value="<?php isset($email) ? setString($email) : false ?>" class="form-control <?php isset($errors['email']) ? setString("is-invalid") : false ?>" id="exampleInputEmail">
            <div class="invalid-feedback">
                <?php isset($errors['email']) ? setString($errors['email']) : false?>
            </div>
        </div>

        <div class="mb-3">
            <label for="exampleInputPassword" class="form-label">Password</label>
            <input type="password" name="password" class="form-control <?php isset($errors['password']) ? setString("is-invalid") : false ?>" id="exampleInputPassword">
            <div class="invalid-feedback">
                <?php isset($errors['password']) ? setString($errors['password']) : false?>
            </div>
        </div>

        <div class="mb-3">
            <label for="exampleInputPassword" class="form-label">Confirm password</label>
            <input type="password" name="password-confirm" class="form-control <?php isset($errors['password']) ? setString("is-invalid") : false ?>" id="exampleInputPassword">
            <div class="invalid-feedback">
                <?php isset($errors['password']) ? setString($errors['password']) : false?>
            </div>
        </div>
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary">Create an account</button>
        </div>
        
    </form>
    <div class="gologin" >
        <p>You have a?<span><a href="./signin.php">Log in.</a></span></p>
    </div>
    <div class="copyright">
        <p class="mt-5 mb-3 text-muted">&copy; Mohammed Aidi</p>
    </div>
    
</main>

<?php require_once ("./includes/footer.php");?>