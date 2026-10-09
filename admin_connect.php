<?php

$time_start = microtime(true); 

include_once "./config.php";
include_once "./include/db_connect.php";
include_once "./include/functions.php";

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['delegated_id'])) {
        header("Location: $redirect_url");
        exit();
}


$search_id = '';
if ( isset($_GET['id']) ) {
   $search_id = $_GET['id'];
} else if ( isset($_POST['id']) ) {
   $search_id = $_POST['id'];
}

// Check for ID in all instances

$target = null;
$target_instance = null;
$firstError = null;
if ($search_id !== '') {
   foreach (poracle_all_apis() as $instance => $api) {
      try {
         $found = $api->get('/api/v2/humans', array('id' => $search_id))['humans'] ?? array();
      } catch (PoracleApiException $e) {
         if ($firstError === null) {
            $firstError = $e;
         }
         continue;
      }
      if ($found) {
         $target = $found[0];
         $target_instance = $instance;
         break;
      }
   }
}

if (!$target) {
	if ($firstError !== null) {
		poracle_fail('', $firstError);
	}
	header("Location: $redirect_url?return=user_not_found");
	exit();
}

if (!admin_may_manage($_SESSION, $target)) {
	header("Location: $redirect_url?return=invalid_auth");
	exit();
}

// Fetch the areas before touching the session so a failure leaves it intact

try {
   $areas = poracle_api_for($target_instance)->get(poracle_human_path($target['id']) . '/areas')['areas'] ?? array();
} catch (PoracleApiException $e) {
   poracle_fail('', $e);
}

$_SESSION['dbname'] = $target_instance;
$_SESSION['id'] = $target['id'];
$_SESSION['username'] = $target['name'];
$_SESSION['type'] = $target['type'];
$_SESSION['notes'] = $target['notes'];
$_SESSION['profile'] = $target['current_profile_no'];
$_SESSION['areas'] = $areas;

// Reset Admin Account

if ( isset($_SESSION['admin_id']) && $_SESSION['id'] == $_SESSION['admin_id'] )
{ 
	$_SESSION['username'] = $_SESSION['username'];
        $_SESSION['type']=$_SESSION['admin_type'];
	$_SESSION['dbname']=$_SESSION['admin_dbname'];
	$_SESSION['notes']='';
}

// Reset Delegated Account

if ( isset($_SESSION['delegated_id']) && $_SESSION['id'] == $_SESSION['delegated_id'] )
{
        $_SESSION['username'] = $_SESSION['username'];
        $_SESSION['type']=$_SESSION['delegated_type'];
        $_SESSION['dbname']=$_SESSION['delegated_dbname'];
	$_SESSION['notes']='';
}

header("Location: $redirect_url");

?>
