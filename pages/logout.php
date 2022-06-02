<?php
    session_start();
    session_destroy();
    session_unset();
   
    require_once("../lib/library.php");
    redirect('../signin.php');
?>