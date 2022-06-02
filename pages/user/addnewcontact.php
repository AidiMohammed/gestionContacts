<?php 
    session_start();
    require_once('../../lib/library.php');

    if(!isset($_SESSION['user_auth']))
        redirect('../../signin.php');

    if($_SERVER['REQUEST_METHOD'] === "POST")
    {
        $errors;

        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $address1 = htmlspecialchars($_POST['address1']);
        $address2 = htmlspecialchars($_POST['address2']);
        $country = htmlspecialchars($_POST['country']);
        $city = htmlspecialchars($_POST['city']);
        $zipCode = htmlspecialchars($_POST['zipCode']);
        $birthday = htmlspecialchars($_POST['birthDay']);
        $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
        $email = htmlspecialchars($_POST['email']);

        if(empty($firstName) || empty($phoneNumber))
        {
            if(empty($firstName))
            {
                $arg_notifiction['theme'] = 'error';
                $arg_notifiction['title'] = 'First name';
                $arg_notifiction['message'] = "First name is required !!";
                $_SESSION['Notification'] = $arg_notifiction;
                $errors['first-name'] = 'First name is required !';
            }
            if(empty($phoneNumber))
            {
                $arg_notifiction['theme'] = 'error';
                $arg_notifiction['title'] = 'Phone number';
                $arg_notifiction['message'] = "Phone number is required !!";
                $_SESSION['Notification'] = $arg_notifiction;
                $errors['phone-number'] = 'phone number is requird !';
            }
        }
        if(!empty($email))
        {
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
            require_once('../../app/models/contact.php');
            $mycontact = new Contact();

            $paylodContact['firstName'] = $firstName;
            $paylodContact['lastName'] = $lastName;
            $paylodContact['address1'] = $address1;            
            $paylodContact['address2'] = $address2;            
            $paylodContact['country'] = $country;            
            $paylodContact['city'] = $city;           
            $paylodContact['zipcode'] = $zipCode;
            $paylodContact['birthday'] = $birthday;           
            $paylodContact['phonenumber'] = $phoneNumber;
            $paylodContact['email'] = $email;           
            $paylodContact['userid'] = $_SESSION['user_auth']->id;           
            
            $mycontact->addNewContact($paylodContact);
 
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Contacts - Add new contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css">
    <link rel="stylesheet" href="../../assets/css/notifications.css">
</head>
<body>
    <?php require_once("../../includes/navbar.php")?>
    <?php

        //notification
        if(isset($_SESSION['Notification']))
        {
            Notifiction($_SESSION['Notification']['theme'],$_SESSION['Notification']['title'],$_SESSION['Notification']['message']);
            unset($_SESSION['Notification']);
        }
    ?>        
            <section>
                <h1>Add new contact</h1>
                <form action="" method="POST">
                <div class="container-xl px-4 mt-4">
                    <div class="row">
                        <div class="col-xl-4">
                            <!-- Profile picture card-->
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header">Picture Contact</div>
                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <img height ='187px' class="img-account-profile rounded-circle mb-2" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                    <!-- Profile picture upload button-->
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Upload picture</label>
                                        <input class="form-control form-control-sm" type="file" id="formFile">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header">New Contact</div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (first name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputFirstName">First name *</label>
                                                <input name="firstName" class="form-control <?php isset($errors['first-name']) ? setString('is-invalid') : false ?>" id="inputFirstName" type="text" placeholder="Enter your first name" value= "" >
                                                <div class="invalid-feedback">
                                                    <?php isset($errors['first-name']) ? setString($errors['first-name']) : false ?>
                                                </div>
                                            </div>
                                            <!-- Form Group (last name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputLastName">Last name </label>
                                                <input name="lastName" class="form-control " id="inputLastName" type="text" placeholder="Enter your last name" value="" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (address 1 )-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="address1">Address1</label>
                                                <input name="address1" class="form-control" id="address1" type="text" placeholder="Enter your address 1" value="" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (address 2)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="address2">Address2</label>
                                                <input name="address2" class="form-control" id="address2" type="text" placeholder="Enter your address 2" value="" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (contry)-->
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="country">Country</label>
                                                <input name="country" class="form-control" id="country" type="text" placeholder="Enter your country" value="" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (city)-->
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="city">City</label>
                                                <input name="city" class="form-control" id="city" type="text" placeholder="Enter your city" value="" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!-- Form Group (zip code)-->
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="zipcode">Zip code</label>
                                                <input name="zipCode" class="form-control" id="zipcode" type="text" placeholder="Zip code" value="" >
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                            <input name="email" class="form-control <?php isset($errors['email']) ? setString('is-invalid') : false?>" id="inputEmailAddress" type="email" placeholder="Enter your email address" value="" >
                                            <div class="invalid-feedback">
                                                <?php isset($errors['email']) ? setString($errors['email']) : false?>
                                            </div>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (phone number)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputPhone">Phone number *</label>
                                                <input name="phoneNumber" class="form-control <?php isset($errors['phone-number']) ? setString('is-invalid') : false?>" id="inputPhone" type="tel" placeholder="Enter your phone number" value="" >
                                                <div class="invalid-feedback">
                                                    <?php isset($errors["phone-number"]) ? setString($errors['phone-number']) : false ?>
                                                </div>
                                            </div>
                                            <!-- Form Group (birthday)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputBirthday">Birthday</label>
                                                <input name="birthDay" class="form-control" id="inputBirthday" type="date" name="birthday" placeholder="Enter your birthday" value="">
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
                                        <button class="btn btn-primary" type="submit">Add Contact</button>
                                        <a class="btn btn-danger"  href="./account.php">CANCEL</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </section>

        </div>
    </main>
    <script src="../../assets/js/notifications.js"></script>
<?php require_once ("../../includes/footer.php");?>