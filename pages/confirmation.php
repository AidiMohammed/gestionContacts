<?php
    require_once'../lib/library.php';
    require_once'../app/models/user.php';

    if(empty($_GET['id']) || empty($_GET['token']))
        redirect('../signin.php');
    else{
        $user_id = $_GET['id'];
        $token_confirmation = $_GET['token'];
        $user = new User();
        $user->confirmdNewUser($user_id,$token_confirmation);
    }