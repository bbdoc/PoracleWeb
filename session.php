
<?php

include "./config.php";
if(session_status() == PHP_SESSION_NONE){
   session_start();
}

$dbnames = explode(",", $dbname);

foreach ($dbnames as &$db) {

   $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
   
   // Check connection
   if ($conn->connect_errno) {
      echo "Failed to connect to MySQL: " . $conn->connect_error;
      exit();
   }

   $sql = "SELECT * from humans WHERE id = '".$_SESSION['id']."'";
   $result = $conn->query($sql) or die(mysqli_error($db));

   if ( $result->num_rows > 0 ) {
	  $_SESSION['dbname'] = $db;
   }

}

