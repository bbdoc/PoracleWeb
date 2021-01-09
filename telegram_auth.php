<?php

include "./config.php";

session_start();

$_SESSION['type']="telegram:user";
$_SESSION['id']=$_GET['id'];
$_SESSION['avatar']=$_GET['photo_url'];
if (isset($_SESSION['username'])) { 
	$_SESSION['username']=$_GET['username']; 
} else {
	$_SESSION['username']=$_GET['first_name'];
}

include "./session.php";

header("Location: $redirect_url");

?>
