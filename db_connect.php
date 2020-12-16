<?php

session_start();

if (!isset($_SESSION['dbname'])) 
{ 
   $dbnames = explode(",", $dbname);
   $_SESSION['dbname'] = $dbnames[0];
} 

$conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $_SESSION['dbname']);

// Check connection
if ($conn->connect_errno) {
   echo "Failed to connect to MySQL: " . $conn->connect_error;
   exit();
}

