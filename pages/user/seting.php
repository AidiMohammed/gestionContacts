<?php 
    session_start();
    require_once('../../lib/library.php');

    if(!isset($_SESSION['user_auth']))
        redirect('../../signin.php');
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Contacts - Seting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css">
</head>
<body>
    <?php require_once("../../includes/navbar.php")?>
            <section>
                <h1><?php echo $_SESSION['user_auth']->user_name?></h1>
            </section>

        </div>
    </main>
<?php require_once ("../../includes/footer.php");?>