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

// Get Config Items from API and Store in Session Variables

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "X-Poracle-Secret: $api_secret\r\n"
  )
);

// Check that API is Running fine

if (!$api = file_get_contents("$api_address/api/config", false, $context)) 
{
   session_destroy();
   header("Location: $redirect_url?return=error_no_api");
   exit();
}

$context = stream_context_create($opts);
$config = file_get_contents("$api_address/api/config", false, $context);
$json = json_decode($config, true);

if ( $json['status']="ok" ) {
   $_SESSION['server_locale'] = $json['locale'];
   $_SESSION['providerURL'] = $json['providerURL'];
   $_SESSION['staticKey'] = $json['staticKey'][0];
   $_SESSION['pvpFilterMaxRank'] = $json['pvpFilterMaxRank'];
   $_SESSION['pvpFilterGreatMinCP'] = $json['pvpFilterGreatMinCP'];
   $_SESSION['pvpFilterUltraMinCP'] = $json['pvpFilterUltraMinCP'];
} else {
   session_destroy();
   header("Location: $redirect_url?return=error_api_nok");
   exit();
}

$areas = file_get_contents("http://127.0.0.1:4201/api/humans/".$_SESSION['id'], false, $context);
$json = json_decode($areas, true);

if ( $json['status']="ok" ) {
   $_SESSION['areas'] = $json['areas'];
} else {
   session_destroy();
   header("Location: $redirect_url?return=error_api_nok");
   exit();
}

