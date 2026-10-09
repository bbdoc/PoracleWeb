<?php

include_once "./config.php";
include_once "./include/functions.php";
include_once "./include/db_connect.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

// Find the instance this human is registered on.

try {
   $instance = poracle_find_human_instance(poracle_all_apis(), $_SESSION['id']);
} catch (PoracleApiException $e) {
   $instance = null;
   $no_api = "True";
}
if ($instance !== null) {
   $_SESSION['dbname'] = $instance;
} elseif (!isset($_SESSION['dbname'])) {
   $_SESSION['dbname'] = poracle_instance_names()[0];
}
$api = poracle_api();

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

// Require PoracleNG (v2 API)

try {
   $health = $api->get('/health');
   if (!poracle_supports_v2($health)) {
      session_destroy();
      header("Location: $redirect_url?return=error_api_not_poracleng");
      exit();
   }
   $json = $api->get('/api/config/poracleWeb');
} catch (PoracleApiException $e) {
   if (!isset($_SESSION['admin_id'])) {
      session_destroy();
      header("Location: $redirect_url?return=error_no_api");
      exit();
   }
   $no_api = "True";
   $json = array();
}

if (!empty($json)) {
   $_SESSION['locale'] = $json['locale'];
   $_SESSION['server_locale'] = $json['locale'];
   $_SESSION['providerURL'] = $json['providerURL'];
   $_SESSION['staticKey'] = ($json['staticKey'][0] ?? '');
   $_SESSION['poracleVersion'] = $json['version'];
   $_SESSION['pvpFilterMaxRank'] = $json['pvpFilterMaxRank'];
   $_SESSION['pvpFilterLittleMinCP'] = $json['pvpFilterLittleMinCP'];
   $_SESSION['pvpFilterGreatMinCP'] = $json['pvpFilterGreatMinCP'];
   $_SESSION['pvpFilterUltraMinCP'] = $json['pvpFilterUltraMinCP'];
   $_SESSION['pvpLittleLeagueAllowed'] = $json['pvpLittleLeagueAllowed'];
   $_SESSION['defaultTemplateName'] = $json['defaultTemplateName'];
   $_SESSION['everythingFlagPermissions'] = $json['everythingFlagPermissions'];
   $_SESSION['maxDistance'] = $json['maxDistance'];
   $_SESSION['poracle_admins'] = array_merge($json['admins']['discord'] ?? array(), $json['admins']['telegram'] ?? array());
   $_SESSION['pvpCaps'] = ($json['pvpCaps'] ?? array());
   $_SESSION['defaultPvpCap'] = $json['defaultPvpCap'];
   $_SESSION['defaultDistance'] = $json['defaultDistance'];
}

// Set Max Distance to 10,726 km if 0 (so no max distance)

if ( empty($_SESSION['maxDistance']) ) { $_SESSION['maxDistance'] = 10726000; }

// Areas this human may select

try {
   $areas = $api->get(poracle_human_path($_SESSION['id']) . '/areas');
   $_SESSION['areas'] = $areas['areas'] ?? array();
} catch (PoracleApiException $e) {
   if ($e->status == 404) {
      header("Location: " . rtrim($redirect_url, '/') . "/");
      exit();
   }
   if (!isset($_SESSION['admin_id'])) {
      session_destroy();
      header("Location: $redirect_url?return=error_api_nok");
      exit();
   }
   $no_api = "True";
}

// Templates

try {
   $_SESSION['templates'] = $api->get('/api/config/templates');
} catch (PoracleApiException $e) {
   $_SESSION['templates'] = array();
}

// Get Delegated Admin from API

try {
   $json = $api->get(poracle_human_path($_SESSION['id']) . '/admin-roles');
   $json['status'] = 'ok';
} catch (PoracleApiException $e) {
   $json = array('status' => 'error');
}

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
   
   if ( in_array($_SESSION['id'], $_SESSION['poracle_admins'] ?? array()) ) 
   { 
	   $_SESSION['channels_admin'] = "True"; 
	   $_SESSION['users_admin'] = "True"; 
	   $_SESSION['poracle_admin'] = "True"; 
   }
   if ( !empty($json['admin']['discord']['users']) ) { 
	   $_SESSION['users_admin'] = "True"; 
   }
   if ( !empty($json['admin']['telegram']['users']) ) { 
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
