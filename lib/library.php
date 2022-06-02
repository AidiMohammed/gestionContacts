<?php
    function redirect($page)
    {
        header("Location: $page");
    }
    function str_random($length)
    {
        $alphabet = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN123456789';
        return substr(str_shuffle(str_repeat($alphabet,$length)),0,$length);
    }
    function setString($str)
    {
        echo $str;
    }
    function Notifiction($theme,$title,$message)
    {
        echo
        "<div class='ncf-container nfc-top-right'>
            <div class='ncf $theme'>
                <button id='romove-notification'>X</button>
                <p class='ncf-title'>$title</p>
                <p class='ncf-message'>$message</p>
            </div>
            <script src='./assets/js/notifications.js' type='text/javascript'></script>
        </div>";
    }
    function Debug($message)
    {
        echo '<pre>';
            print_r($message);
        echo '</pre>';
    }
?>