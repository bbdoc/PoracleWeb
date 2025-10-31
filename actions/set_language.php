<?php

include_once "../config.php";
include_once "../include/db_connect.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

$_SESSION['locale']=$_GET['lng'];

// Update Language in DB

$stmt = $conn->prepare("UPDATE humans SET language = ? WHERE id = ?");
$stmt->bind_param("ss", $_GET['lng'], $_SESSION['id']);
$stmt->execute() or die(mysqli_error($conn));
$stmt->close();

header("Location: $redirect_url");

?>
