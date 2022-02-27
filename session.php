<?php

include_once "./config.php";
include_once "./include/functions.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

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

$context = stream_context_create($opts);

// Check that API is Running fine

if (!$api = @file_get_contents("$api_address/api/config/poracleWeb", false, $context) ) 
{
   if (!isset($_SESSION['admin_id']))
   {
      session_destroy();
      header("Location: $redirect_url?return=error_no_api");
      exit();
   } else
   {
     $no_api = "True";
   }
}

// Get Config Items from API

$config = @file_get_contents("$api_address/api/config/poracleWeb", false, $context);
$json = json_decode($config, true);

if ( $json['status']=="ok" ) {
   $_SESSION['locale'] = $json['locale'];
   $_SESSION['server_locale'] = $json['locale'];
   $_SESSION['providerURL'] = $json['providerURL'];
   $_SESSION['staticKey'] = $json['staticKey'][0];
   $_SESSION['poracleVersion'] = $json['version'];
   $_SESSION['pvpFilterMaxRank'] = $json['pvpFilterMaxRank'];
   $_SESSION['pvpFilterLittleMinCP'] = $json['pvpFilterLittleMinCP'];
   $_SESSION['pvpFilterGreatMinCP'] = $json['pvpFilterGreatMinCP'];
   $_SESSION['pvpFilterUltraMinCP'] = $json['pvpFilterUltraMinCP'];
   $_SESSION['pvpLittleLeagueAllowed'] = $json['pvpLittleLeagueAllowed'];
   $_SESSION['defaultTemplateName'] = $json['defaultTemplateName'];
   $_SESSION['everythingFlagPermissions'] = $json['everythingFlagPermissions'];
   $_SESSION['maxDistance'] = $json['maxDistance'];
   $_SESSION['poracle_admins'] = array_merge($json['admins']['discord'],$json['admins']['telegram']);
   $_SESSION['pvpCaps'] = $json['pvpCaps'];
   $_SESSION['defaultPvpCap'] = $json['defaultPvpCap'];
} else if (!isset($_SESSION['admin_id'])) {
   session_destroy();
   header("Location: $redirect_url?return=error_api_nok");
   exit();
} else {
   $no_api = "True";

}

// Set Max Distance to 10,726 km if 0 (so no max distance)

if ( $_SESSION['maxDistance'] == 0 ) { $_SESSION['maxDistance'] = 10726000; }

// Get Areas from API

$areas = @file_get_contents("$api_address/api/humans/".rawurlencode($_SESSION['id']), false, $context);
$json = json_decode($areas, true);

if ( $json['status']=="ok" ) {
   $_SESSION['areas'] = $json['areas'];
} else if ( $json['message'] == "User not found" ) {
   header("Location: $redirect_url?$redirect_page");
   exit();
} else if (!isset($_SESSION['admin_id'])) {
   session_destroy();
   header("Location: $redirect_url?return=error_api_nok");
   exit();
} else {
   $no_api = "True";
}

// Get Templates from API

$templates = @file_get_contents("$api_address/api/config/templates", false, $context);
$json = json_decode($templates, true);

$_SESSION['templates'] = $json;

// Get Delegated Admin from API

$delegated = @file_get_contents("$api_address/api/humans/".$_SESSION['id']."/getAdministrationRoles", false, $context);
$json = json_decode($delegated, true);

if ( $json['status']=="ok" ) {

   $_SESSION['delegated_channels'] = $json['admin'];
   $_SESSION['delegated_count'] = 0;

   // Count Number of Delegated Channels Users has

   if (isset($json['admin']['discord']['channels'])) 
   { 
	   $_SESSION['delegated_count'] = $_SESSION['delegated_count'] + count($json['admin']['discord']['channels']);
   } 
   if (isset($json['admin']['discord']['webhooks'])) 
   { 
	   $_SESSION['delegated_count'] = $_SESSION['delegated_count'] + count($json['admin']['discord']['webhooks']);
   } 
   if (isset($json['admin']['telegram']['channels'])) 
   { 
	   $_SESSION['delegated_count'] = $_SESSION['delegated_count'] + count($json['admin']['telegram']['channels']);
   } 

   // Set Roles depending on API results
   
   if ( in_array($_SESSION['id'],$_SESSION['poracle_admins']) ) 
   { 
	   $_SESSION['channels_admin'] = "True"; 
	   $_SESSION['users_admin'] = "True"; 
	   $_SESSION['poracle_admin'] = "True"; 
   }
   if ( isset($json['admin']['discord']['users']) && $json['admin']['discord']['users'] == "true" ) { 
	   $_SESSION['users_admin'] = "True"; 
   }
   if ( isset($json['admin']['telegram']['users']) && $json['admin']['telegram']['users'] == "true" ) { 
	   $_SESSION['users_admin'] = "True"; 
   }
   if ( $_SESSION['delegated_count'] > 0 ) 
   { 
	   $_SESSION['channels_admin'] = "True"; 
   }

   // Set Return Values for all channel or User admins
   
   if ( isset($_SESSION['channels_admin']) || isset($_SESSION['users_admin']) )
   {
	$_SESSION['delegated_id'] = $_SESSION['id'];
        $_SESSION['delegated_username'] = $_SESSION['username'];
        $_SESSION['delegated_dbname'] = $_SESSION['dbname'];
        $_SESSION['delegated_type'] = $_SESSION['type'];
   }

}

set_locale();
