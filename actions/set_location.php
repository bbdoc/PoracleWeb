<?php

include "../config.php";
include "../db_connect.php";

if ( isset($_GET['lat']) &&  isset($_GET['lon']) ) {

   $lat = $_GET['lat'];
   $lon = $_GET['lon'];

} else {

   $config = file_get_contents("$poracle_dir/config/local.json");
   $json = json_decode($config, true);
   foreach ($json as $key => $value) {
      if ($key == "geocoding") {
         $nominatim=$value['providerURL'];
         $statickey=$value['staticKey'][0];
      }
   }


   $street = str_replace(" ", "%20", $_POST['street']);
   $city = str_replace(" ", "%20", $_POST['city']);

   $filepath="$nominatim/?addressdetails=1&q=".$street."%20".$city."&format=json&limit=1";
   if ( strlen($statickey, "UTF-8") == 32  ) { 
	   $filepath.="&key=".$statickey;
   }

   $request = file_get_contents($filepath);

   if ( $request == "[]" ) { 
      header("Location: $redirect_url?return=error_update_location");
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

$stmt = $conn->prepare("UPDATE humans set latitude = ?, longitude = ? WHERE id = ?");
if (false === $stmt) {
  header("Location: $redirect_url?return=sql_error&phase=ESL1&sql=$stmt->error");
  exit();
}
$rs = $stmt->bind_param("sss", $lat, $lon, $_SESSION['id']);
if (false === $rs) {
  header("Location: $redirect_url?return=sql_error&phase=ESL2&sql=$stmt->error");
  exit();
}
$rs = $stmt->execute();
if (false === $rs) {
  header("Location: $redirect_url?return=sql_error&phase=ESL3&sql=$stmt->error");
  exit();
}

header("Location: $redirect_url?return=success_update_location");
exit();


?>
