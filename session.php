
<?php

include "./config.php";
if(session_status() == PHP_SESSION_NONE){
   session_start();
}

$dbnames = explode(",", $dbname);

foreach ($dbnames as &$db) {
  $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
  $sql = "SELECT * from humans WHERE id = '".$_SESSION['id']."'";
  $result = $conn->query($sql);
  if ( $result->num_rows > 0 ) {
	  $_SESSION['dbname'] = $db;
  }
}
