<?php

include "./config.php";

session_start(); // initialize the session variables

session_unset(); // clear the $_SESSION variable

if(isset($_COOKIE[session_name()])) {
    setcookie(session_name(),'',time()-3600); # Unset the session id
}

session_destroy(); // finally destroy the session

header("Location: $redirect_url?return=success_logout");

