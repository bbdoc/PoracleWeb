<?php

include_once "../config.php";
include_once "../include/db_connect.php";
include_once "../include/functions.php";


$api = poracle_api();
$human = $_SESSION['id'];
$profile = (int) $_SESSION['profile'];

if ( isset($_GET['action']) && $_GET['action'] == "delete" ) {

  try {
    $api->delete(poracle_human_path($human) . '/location', array('profile' => $profile));
    foreach (array('pokemon','raid','egg','quest','invasion','incident','lure','nest','gym','fort','maxbattle') as $type) {
      poracle_track_set_field($api, $human, $type, poracle_rules($type), 'distance', null, $profile);
    }
  } catch (PoracleApiException $e) {
    poracle_fail('area', $e);
  }
  header("Location: $redirect_url?type=display&page=area&return=success_delete_location");
  exit();

} else if ( isset($_GET['lat']) &&  isset($_GET['lon']) ) {

   $lat = $_GET['lat'];
   $lon = $_GET['lon'];

} else {

   $street = trim($_POST['street']);
   $city = trim($_POST['city']);

   $params = [
       'addressdetails' => 1,
       'q' => trim($street . ' ' . $city),
       'format' => 'json',
       'limit' => 1,
   ];

   if (strlen($_SESSION['staticKey']) == 32) {
       $params['key'] = $_SESSION['staticKey'];
   }

   $filepath = rtrim($_SESSION['providerURL'], '/')
       . '/search?'
       . http_build_query($params);

   $request = file_get_contents($filepath);

   if ( $request == "[]" ) { 
      header("Location: $redirect_url?type=display&page=area&return=error_update_location");
      exit();
   }   

   $json = json_decode($request, true);

   foreach ($json as $key => $value) { 
      foreach ($value as $key => $value2) { 
         if ($key == "lat") { $lat = $value2; }
         if ($key == "lon") { $lon = $value2; }
      }
   }

}

// Set the location of the viewed profile (403 = outside the allowed area)

try {
  $api->post(poracle_human_path($human) . '/location', array('lat' => (float) $lat, 'lon' => (float) $lon), array('profile' => $profile));
} catch (PoracleApiException $e) {
  poracle_fail('area', $e);
}

header("Location: $redirect_url?type=display&page=area&return=success_update_location");
exit();

?>
