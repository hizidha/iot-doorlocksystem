<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: ?page=portal");
    }

    $_SESSION['logoutMsg'] = "You have successfully logged out of your account, see you soon!";

    header("Location: ?page=portal");
    exit();
?>