<?php

include "../config.php";
include "../db_connect.php";


if ( isset($_GET['action']) && $_GET['action'] == "delete" ) {

  $lat = "0.0000000000";
  $lon = "0.0000000000";

  $sql = "UPDATE monsters set distance = 0 WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '".$_SESSION['profile']."'";
  $result = $conn->query($sql);
  $sql = "UPDATE raid set distance = 0 WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '".$_SESSION['profile']."'";
  $result = $conn->query($sql);
  $sql = "UPDATE egg set distance = 0 WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '".$_SESSION['profile']."'";
  $result = $conn->query($sql);
  $sql = "UPDATE quest set distance = 0 WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '".$_SESSION['profile']."'";
  $result = $conn->query($sql);
  $sql = "UPDATE invasion set distance = 0 WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '".$_SESSION['profile']."'";
  $result = $conn->query($sql);

} else if ( isset($_GET['lat']) &&  isset($_GET['lon']) ) {

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
   if ( strlen($statickey) == 32  ) { 
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

// Update Lat and Lon from Humans if current profile is active

$stmt = $conn->prepare("UPDATE humans set latitude = ?, longitude = ? WHERE id = ? AND current_profile_no = ?");
if (false === $stmt) {
  header("Location: $redirect_url?return=sql_error&phase=ESLH1&sql=$stmt->error");
  exit();
}
$rs = $stmt->bind_param("sssi", $lat, $lon, $_SESSION['id'], $_SESSION['profile']);
if (false === $rs) {
  header("Location: $redirect_url?return=sql_error&phase=ESLH2&sql=$stmt->error");
  exit();
}
$rs = $stmt->execute();
if (false === $rs) {
  header("Location: $redirect_url?return=sql_error&phase=ESLH3&sql=$stmt->error");
  exit();
}

// Update Lat and Lon from Profile if exist

$stmt = $conn->prepare("UPDATE profiles set latitude = ?, longitude = ? WHERE id = ? AND profile_no = ?");
if (false === $stmt) {
  header("Location: $redirect_url?return=sql_error&phase=ESLP1&sql=$stmt->error");
  exit();
}
$rs = $stmt->bind_param("sssi", $lat, $lon, $_SESSION['id'], $_SESSION['profile']);
if (false === $rs) {
  header("Location: $redirect_url?return=sql_error&phase=ESLP2&sql=$stmt->error");
  exit();
}
$rs = $stmt->execute();
if (false === $rs) {
  header("Location: $redirect_url?return=sql_error&phase=ESLP3&sql=$stmt->error");
  exit();
}


if ( isset($_POST['delete']) ) {
    header("Location: $redirect_url?return=success_delete_location");
    exit();
} else {
    header("Location: $redirect_url?return=success_update_location");
    exit();
}


?>
