<?php 
    session_start();
    require_once('../../lib/library.php');

    if(!isset($_SESSION['user_auth']))
        redirect('../../signin.php');

    if($_SERVER['REQUEST_METHOD'] === "POST")
    {
        $errors;
        $userName = htmlspecialchars($_POST['userName']);//table users
        $email = htmlspecialchars($_POST['email']);//table users

        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $address1 = htmlspecialchars($_POST['address1']);
        $address2 = htmlspecialchars($_POST['address2']);
        $country = htmlspecialchars($_POST['country']);
        $city = htmlspecialchars($_POST['city']);
        $zipCode = htmlspecialchars($_POST['zipCode']);
        $birthday = htmlspecialchars($_POST['birthDay']);
        $phoneNumber = htmlspecialchars($_POST['phoneNumber']);

        /*$man = htmlspecialchars($_POST['man']);
        $women = htmlspecialchars($_POST['women']);
        $student = htmlspecialchars($_POST['student']);
        $jobseeker = htmlspecialchars($_POST['jobseeker']);
        $worker = htmlspecialchars($_POST['worker']);*/

        if(empty($userName) || empty($email))
            {
                if(empty($userName))
                {
                    $arg_notifiction['theme'] = 'error';
                    $arg_notifiction['title'] = 'User name';
                    $arg_notifiction['message'] = "User name is required !!";
                    $_SESSION['Notification'] = $arg_notifiction;
                    $errors['userName'] = "User name is required !!";
                }
                if(empty($email))
                {
                    $arg_notifiction['theme'] = 'error';
                    $arg_notifiction['title'] = 'Adress email';
                    $arg_notifiction['message'] = "Email is required !!";
                    $_SESSION['Notification'] = $arg_notifiction;
                    $errors['email'] = "Email is required !!";
                }
            }
        else
        {
            if(strlen($userName) < 5)
            {
                $arg_notifiction['theme'] = 'error';
                $arg_notifiction['title'] = 'User name';
                $arg_notifiction['message'] = "A user name must contain at least 5 characters !!";
                $_SESSION['Notification'] = $arg_notifiction;
                $errors['userName'] = "A user name must contain at least 5 characters !!";
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                $arg_notifiction['theme'] = 'error';
                $arg_notifiction['title'] = 'Address email';
                $arg_notifiction['message'] = "Email address is invalid";
                $_SESSION['Notification'] = $arg_notifiction;
                $errors['email'] = "Email address is invalid";
            }
        }
        if(empty($errors))
        {
            require_once('../../app/models/user.php');
            require_once('../../app/models/user_profile.php');
            $myUser = new User();
            $myProfile = new UserProfil();

            if($userName != $_SESSION['user_auth']->user_name || $email != $_SESSION['user_auth']->email)
            {
                $errorsUsernameOrEmail = $myUser->checkUsernameEmailIfExist($email,$userName);

                if($userName != $_SESSION['user_auth']->user_name)
                    if(isset($errorsUsernameOrEmail['username']))
                    {
                        $arg_notifiction['theme'] = 'error';
                        $arg_notifiction['title'] = 'User name';
                        $arg_notifiction['message'] = $errorsUsernameOrEmail['username'];
                        $_SESSION['Notification'] = $arg_notifiction;
                        $errors['userName'] = $errorsUsernameOrEmail['username'];
                    }

                if($email != $_SESSION['user_auth']->email)
                    if(isset($errorsUsernameOrEmail['email']))
                    {
                        $arg_notifiction['theme'] = 'error';
                        $arg_notifiction['title'] = 'Email';
                        $arg_notifiction['message'] = $errorsUsernameOrEmail['email'];
                        $_SESSION['Notification'] = $arg_notifiction;
                        $errors['email'] = $errorsUsernameOrEmail['email'];
                    }
            }

            if(empty($errors))
            {

                if($userName != $_SESSION['user_auth']->user_name)
                {
                    if($myUser->userUpdateUserName($userName,$_SESSION['user_auth']->id))//update tabe users db
                    $_SESSION['user_auth']->user_name = $userName;//update session user name
                }
                if($email != $_SESSION['user_auth']->email)
                {
                    if($myUser->userUpdateEmail($email,$_SESSION['user_auth']->id))
                    $_SESSION['user_auth']->email = $email;
                }
                
                $paylodProfile = new stdClass;
                $paylodProfile->firstName = $firstName;
                $paylodProfile->lastName = $lastName;
                $paylodProfile->address1 = $address1;
                $paylodProfile->address2 = $address2;
                $paylodProfile->country = $country;
                $paylodProfile->city = $city;
                $paylodProfile->zipCode =$zipCode;
                $paylodProfile->birthday =$birthday;
                $paylodProfile->phoneNumber =$phoneNumber;
                $paylodProfile->phoneNumber =$phoneNumber;
                $paylodProfile->picture = "/image/avatar.jpeg";
            
                $myProfile->updateProfile($_SESSION['user_auth']->id,$paylodProfile);
                $_SESSION['Profile'] = $myProfile->getProfileByUserId($_SESSION['user_auth']->id);
            
                $arg_notifiction['theme'] = 'success';
                $arg_notifiction['title'] = 'Profile Update succsessfully';
                $arg_notifiction['message'] = "Your profile has been updated successfully";
                $_SESSION['Notification'] = $arg_notifiction;
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
    <title>Save Contacts - Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css">
    <link rel="stylesheet" href="../../assets/css/profil.css">
    <link rel="stylesheet" href="../../assets/css/modalwelcom.css">
    <link rel="stylesheet" href="../../assets/css/notifications.css">

</head>
<body>
    <?php require_once("../../includes/navbar.php")?>
            <section>
                <?php
                    //show modale welcome if is first use from this user
                        if($_SESSION['user_auth']->first_use == 1)
                        {
                            require_once('../../app/models/user.php');
                            $user = new User();
                            $user->notFirstUse($_SESSION['user_auth']->id);
                            require_once('../../includes/modalwelcom.php');
                            $_SESSION['user_auth']->first_use = 0;
                        }
                ?>
                <?php
                    if(isset($_SESSION['Notification']))
                    {
                        Notifiction($_SESSION['Notification']['theme'],$_SESSION['Notification']['title'],$_SESSION['Notification']['message']);
                        unset($_SESSION['Notification']);
                    }
                ?>
                <div class="container-xl px-4 mt-4">
                    <div class="row">
                        <div class="col-xl-4">
                            <!-- Profile picture card-->
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header">Profile Picture</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <img height ='187px' class="img-account-profile rounded-circle mb-2" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                    <!-- Profile picture upload button-->
                                    <!--<input width="30px" class="btn btn-primary" type="file">-->
                                    <button class="btn btn-btn-group-sm btn-primary">Upload picture</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header">Account Details</div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <!-- Form Group (username)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputUsername">Username * (how your name will appear to other users on the site)</label>
                                            <input name="userName" class="form-control <?php isset($errors['userName']) ? setString('is-invalid') : setString('is-valid')?>" id="inputUsername" type="text" placeholder="Enter your username" value="<?php echo $_SESSION['user_auth']->user_name?>">
                                            <div class="invalid-feedback">
                                                <?php isset($errors['userName']) ? setString($errors['userName']) : false?>
                                            </div>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (first name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputFirstName">First name</label>
                                                <input name="firstName" class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" value= "<?php echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->first_name : NULL ?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (last name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputLastName">Last name</label>
                                                <input name="lastName" class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" value="<?php echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->last_name : NULL?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (address 1 )-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="address1">Address1</label>
                                                <input name="address1" class="form-control" id="address1" type="text" placeholder="Enter your address 1" value="<?php echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->address1 : NULL ?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (address 2)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="address2">Address2</label>
                                                <input name="address2" class="form-control" id="address2" type="text" placeholder="Enter your address 2" value="<?php  echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->address2 : NULL?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (contry)-->
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="country">Country</label>
                                                <input name="country" class="form-control" id="country" type="text" placeholder="Enter your country" value="<?php echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->country : NULL ?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (city)-->
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="city">City</label>
                                                <input name="city" class="form-control" id="city" type="text" placeholder="Enter your city" value="<?php  echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->city : NULL ?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (zip code)-->
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="zipcode">Zip code</label>
                                                <input name="zipCode" class="form-control" id="zipcode" type="text" placeholder="Zip code" value="<?php  echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->zip_code : NULL?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputEmailAddress">Email address *</label>
                                            <input name="email" class="form-control <?php isset($errors['email']) ? setString('is-invalid') : setString('is-valid')?>" id="inputEmailAddress" type="email" placeholder="Enter your email address" value="<?php echo $_SESSION['user_auth']->email?>" >
                                            <div class="invalid-feedback">
                                                <?php isset($errors['email']) ? setString($errors['email']) : false?>
                                            </div>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (phone number)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                                <input name="phoneNumber" class="form-control" id="inputPhone" type="tel" placeholder="Enter your phone number" value="<?php  echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->phone_number : NULL ?>" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (birthday)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputBirthday">Birthday</label>
                                                <input name="birthDay" class="form-control" id="inputBirthday" type="date" name="birthday" placeholder="Enter your birthday" value="<?php echo isset($_SESSION['Profile']) ? $_SESSION['Profile']->birthday : NULL  ?>">
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form sexe & staus-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1">Sexe</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="man" id="man" value="option1" >
                                                    <label name="man" class="form-check-label" for="man">Man</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="women" id="women" value="option2">
                                                    <label name="women" class="form-check-label" for="women">Women</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1">Status</label>
                                                <div class="form-check">
                                                    <input name="student" class="form-check-input" type="radio" name="student" id="student" value="option1" >
                                                    <label name="student" class="form-check-label" for="student">Student</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jobseeker" id="jobseeker" value="option2">
                                                    <label name="jobseeker" class="form-check-label" for="jobseeker">Jobseeker</label>
                                                </div>
                                                <div class="form-check disabled">
                                                    <input class="form-check-input" type="radio" name="worker" id="worker" value="option3">
                                                    <label name="worker" class="form-check-label" for="worker">Worker</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" type="submit">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
    <script src="../../assets/js/notifications.js"></script>
<?php require_once ("../../includes/footer.php");?>