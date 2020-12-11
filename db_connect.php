<?php

session_start();

if (!isset($_SESSION['dbname'])) 
{ 
   $dbnames = explode(",", $dbname);
   $_SESSION['dbname'] = $dbnames[0];
} 

$conn = new mysqli($dbhost, $dbuser, $dbpass, $_SESSION['dbname']);

?>
