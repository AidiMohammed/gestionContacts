<?php 
    session_start();

    if(!isset($_SESSION['user_auth']))
    {
        require_once('../../lib/library.php');
        redirect('../../signin.php');
    }
    
    if($_SESSION['user_auth']->first_use == 1)
    {
        require_once('../../lib/library.php');
        redirect('profile.php');
    }

    require_once('../../app/models/contact.php');
    
    $myContact = new Contact();

    $myContacts = $myContact->getAllContacts($_SESSION['user_auth']->id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Contacts - My account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css">
    <link rel="stylesheet" href="../../assets/css/account.css">
    

</head>
<body>
    <?php require_once("../../includes/navbar.php")?>
        <section>
            <div class="empty-list">
                <img class="mb-4" src="../../assets/img/empty-box.svg" alt="image login" width="700" height="530">  
                <h1>Empty list</h1>
                <h2>You have not added any contacts to your contact list to add your first contact click ont the button below</h2>  
                <a href="./addnewcontact.php">Add New Contact</a>
            </div>

        </section>
        </div><!--div container bootstrap -->
    </main>
<?php require_once ("../../includes/footer.php");?>