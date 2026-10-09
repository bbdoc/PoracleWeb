<?php

include_once "../config.php";
include_once "../include/db_connect.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

// Update Language (v2 validates the locale)

try {
  poracle_api()->post(poracle_human_path($_SESSION['id']) . '/language', array('language' => $_GET['lng']));
} catch (PoracleApiException $e) {
  poracle_fail('', $e);
}

$_SESSION['locale']=$_GET['lng'];

header("Location: $redirect_url");

?>
