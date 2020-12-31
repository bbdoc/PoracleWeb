<?php
include "./config.php";
session_start();
$_SESSION['locale']=$_GET['lng'];
header("Location: $redirect_url");
?>
