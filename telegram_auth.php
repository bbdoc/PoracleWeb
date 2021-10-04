<?php

include "./config.php";
include "include/defaults.php";
include "include/db_connect.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

// Check Telegram Bot Auth

$data_check_string = "auth_date=".$_GET['auth_date']."\n";
$data_check_string .= "first_name=".$_GET['first_name']."\n";
$data_check_string .= "id=".$_GET['id']."\n";
$data_check_string .= "last_name=".$_GET['last_name']."\n";
$data_check_string .= "photo_url=".$_GET['photo_url'];

$secret_key = hash('sha256', $telegram_bot_token, true);
$hash = hash_hmac('sha256', $data_check_string, $secret_key);

if ( $hash != $_GET['hash']) 
{ 
	header("Location: $redirect_url/?return=invalid_auth"); 
	exit();
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
else if ( version_compare($_SESSION['poracleVersion'], $min_poracle_version) < 0 ) 
{
   header("Location: $redirect_url?type=display&page=server_settings");
}

else 
{
   header("Location: $redirect_url");
}

?>
