<?php
    require_once('./lib/library.php');
    session_start();
    
    if(isset($_SESSION['user_auth']))
        redirect('./pages/user/account.php');

    if($_SERVER['REQUEST_METHOD'] === "POST")
        {
            $errors;
            $userName = htmlspecialchars($_POST['user-name']); 
            $password = htmlspecialchars($_POST['password']);

            if(empty($userName) || empty($password))
            {
                if(empty($userName))
                    $errors['userName'] = "user name is required !!</br>";
                if(empty($password))
                    $errors ['password'] = "password is required !!</br>";
            }else
            {
                require_once("./app/models/user.php");
                require_once("./app/models/user_profile.php");
                $user = new User();
                $profileUser = new UserProfil();

                $user_auth =  $user->userLogin($userName,$password);

                if($user_auth === "user not confirmed")
                {
                    session_start();
                    $arg_notifiction['theme'] = 'info';
                    $arg_notifiction['title'] = 'Confirm your account';
                    $arg_notifiction['message'] = "You need to confrim you account.";
                    $_SESSION['Notification'] = $arg_notifiction;
                    redirect("signin.php");
                    exit();
                }
                if($user_auth === "user not enabled")
                {
                    session_start();
                    $arg_notifiction['theme'] = 'warning';
                    $arg_notifiction['title'] = 'Account Disabled';
                    $arg_notifiction['message'] = "Your account has been disabled if you have any questions or concerns,you can visit our Help Center.";
                    $_SESSION['Notification'] = $arg_notifiction;
                    redirect("signin.php");
                    exit();
                }
                if ($user_auth === false)
                    $errors['authentification-error'] = 'incorrect usernam or password';
                else
                {
                    session_start();
                    $_SESSION['Profile'] = $profileUser->getProfileByUserId($user_auth->id);
                    $_SESSION['user_auth'] = $user_auth;
                    redirect('./pages/user/account.php');
                    exit();
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
    <title>Save contacts - signin</title>

    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/signin.css">
    <link rel="stylesheet" href="./assets/css/notifications.css">
</head>
<body class = "text-center">
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

<?php
    if(isset($_SESSION['Notification']))
    {
        Notifiction($_SESSION['Notification']['theme'],$_SESSION['Notification']['title'],$_SESSION['Notification']['message']);
        session_unset();
        session_destroy();
    }
?>

<main class="form-signin">
    
    <form action="" method="POST" >
        <div class="div-img">
            <img class="mb-4" src="./assets/img/undraw_secure_login_pdn4.svg" alt="image login" width="300" height="250">    
        </div>
        
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <?php if(isset($errors['authentification-error'])):?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div><?=$errors['authentification-error']?></div>
            </div>
        <?php endif;?>

        <div class="form-floating">
            <input type="text" name="user-name" value="<?php (isset($userName) && !empty($userName)) ? setString($userName) : false?>" class="form-control" id="floatingInput" placeholder="User name">
            <?php if(isset($errors['userName'])):?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div><?=$errors['userName']?></div>
                </div>
            <?php endif;?>
        <label for="floatingInput">User name</label>
        </div>

        <div class="form-floating">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
        <?php if(isset($errors['password'])):?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div><?=$errors['password']?></div>
                </div>
            <?php endif;?>
            <label for="floatingPassword">Password</label>
        </div>

        <div class="remember-forgotpwd">
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>        
            <a href="./pages/forgotpwd.php">Forgot password ?</a>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    </form>
    <div class="creat-account" >
        <p>You do not have?<span><a href="./signup.php">Creat new account.</a></span></p>
    </div>
    <p class="mt-5 mb-3 text-muted">&copy; Mohammed Aidi</p>

    
    <?php require_once ("./includes/footer.php");?>