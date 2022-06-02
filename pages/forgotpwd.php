<?php
    require_once'../lib/library.php';
    session_start();
    if(isset($_SESSION['user_auth']))
    {
        redirect("user/account.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === "POST")
    {
        $error;
        $email = htmlspecialchars($_POST['email']);
        if(empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            if(empty($email))
                $error = 'Email is required !';
            else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
                $error = 'Email not valid';
        }
        else
        {
            require_once("../app/models/user.php");
            $restpwd = new User();
            //$restpwd->userForgottPassword($email) === false ? $error = "Email not found" : $error = "";
            if($restpwd->userForgottPassword($email))
            {
                session_start();
                $arg_notifiction['theme'] = 'success';
                $arg_notifiction['title'] = 'check your email';
                $arg_notifiction['message'] = 'We sent you an email with instructions to reset your password.';
                $_SESSION['Notification'] = $arg_notifiction;
                redirect("../signin.php");
            }
            else
                $error = "Email not found";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save contacts - forgot password</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/forgorpwd.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body >
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

    <div class="container h-100">
    	<div class="row h-100">
			<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
				<div class="d-table-cell align-middle">
					<div class="text-center mt-4">
						<h1 class="h2">Reset password</h1>
						<p class="lead">
							Enter your email to reset your password.
						</p>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="m-sm-4">
								<form action="" method="POST">
									<div class="form-group">
										<label>Email</label>
										<input class="form-control form-control <?php isset($error) ? setString('is-invalid') : false?>" type="email" name="email" placeholder="Enter your email">
                                        <div class="invalid-feedback">
                                            <?php isset($error) ? setString($error) : false?>
                                        </div>
									</div>
									<div class="text-center mt-3">
                                        <div class="group-btn">
                                            <button type="submit" class="btn btn-primary">Reset password</button>
                                            <a class="btn btn-danger" href="../signin.php"><i class="fa-solid fa-circle-arrow-left"></i>Back to login</a>
                                        </div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>