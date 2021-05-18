<?php

include "./config.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

$_SESSION['type']="telegram:user";
$_SESSION['id']=$_GET['id'];
$_SESSION['avatar']=$_GET['photo_url'];
if (isset($_SESSION['username'])) { 
	$_SESSION['username']=$_GET['username']; 
} else {
	$_SESSION['username']=$_GET['first_name']." ".$_GET['last_name'];
}

include "./session.php";

if (isset($no_api) && $no_api == "True")
{
   header("Location: $redirect_url?type=display&page=server_settings");
}
else 
{
   header("Location: $redirect_url");
}

?>
