<?php

include "./config.php";
include "./functions.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

set_locale();

$dbnames = explode(",", $dbname);

foreach ($dbnames as &$db) {

   $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
   
   // Check connection
   if ($conn->connect_errno) {
      echo "Failed to connect to MySQL: " . $conn->connect_error;
      exit();
   }

   $sql = "SELECT * from humans WHERE id = '".$_SESSION['id']."'";
   $result = $conn->query($sql) or die(mysqli_error($conn));

   if ( $result->num_rows > 0 ) {
	  $_SESSION['dbname'] = $db;
   }

}


// Set Admin Variables

if (isset($admin_id)) {
   $admins = explode(",", $admin_id);
   foreach ($admins as &$admin) { 
      if ($_SESSION['id'] == $admin)
      {
   	   $_SESSION['admin_id'] = $_SESSION['id'];
	   $_SESSION['admin_username'] = $_SESSION['username'];
	   $_SESSION['admin_dbname'] = $_SESSION['dbname'];
	   $_SESSION['admin_type'] = $_SESSION['type'];
      } 
   } 
}
