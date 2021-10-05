<?php

include_once "../config.php";
include_once "../include/db_connect.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

$_SESSION['locale']=$_GET['lng'];

// Update Language in DB

$sql = "UPDATE humans set language  = '".$_GET['lng']."'  WHERE id = '" . $_SESSION['id'] . "'"; 
$result = $conn->query($sql) or die(mysqli_error($conn));

header("Location: $redirect_url");

?>
