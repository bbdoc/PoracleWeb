<?php

include "../config.php";
include "../db_connect.php";

session_start();

$_SESSION['locale']=$_GET['lng'];

// Update Language in DB

$sql = "UPDATE humans set language  = '".$_GET['lng']."'  WHERE id = '" . $_SESSION['id'] . "'"; 
$result = $conn->query($sql) or die(mysqli_error($conn));

header("Location: $redirect_url");

?>
