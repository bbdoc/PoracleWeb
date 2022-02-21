<?php

include_once "./config.php";
include_once "./include/db_connect.php";
include_once "./include/functions.php";
include_once "./include/defaults.php";

set_locale();

if (isset($scan_dbtype) && $scan_dbtype == "MAD") {
    include_once "./include/db_mad.php";
}
if (isset($scan_dbtype) && $scan_dbtype == "RDM") {
    include_once "./include/db_rdm.php";
}

if (isset($custom_title)) {
    $title = $custom_title;
} else {
    $title = "PoracleWeb";
}

if (!isset($_SESSION['avatar']) || false === @file_get_contents($_SESSION['avatar'], 0, null, 0, 1)) {
	$avatar = "$redirect_url/img/no_avatar.png";
} else {
	$avatar = $_SESSION['avatar'];
}

// Set Profile to current if not yet set

if (!isset($_SESSION['profile']) && isset($_SESSION['id'])) {
   $sql = "SELECT current_profile_no FROM humans WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $_SESSION['profile'] = $row['current_profile_no'];
   }
}

// Check if user has Multiple Profiles

if (isset($_SESSION['id'])) {
   $sql = "SELECT name FROM profiles WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   $_SESSION['number_of_profiles'] = $result->num_rows;
}

// Get Profile Name


if (isset($_SESSION['id'])) {
   $sql = "SELECT name FROM profiles WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
   $result = $conn->query($sql);
   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $_SESSION['profile_name'] = $row['name'];
      }
   } else {
         $_SESSION['profile_name'] = i8ln("Default");
   }
}

// Get Active Profile

if (isset($_SESSION['id'])) {
   $sql = "SELECT current_profile_no from humans WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
      $_SESSION['current_profile'] = $row['current_profile_no'];
   }
}

// Check for Cleaned

if (isset($_SESSION['id'])) {

   $sql = "select min(clean) clean FROM monsters WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_mon_cleaned = $row['clean'];
   }
   
   $sql = "select min(clean) clean FROM (select id, clean from raid UNION select id, clean from egg) raidegg WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_raid_cleaned = $row['clean'];
   }
   
   $sql = "select min(clean) clean FROM quest WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_quest_cleaned = $row['clean'];
   }

   $sql = "select min(clean) clean FROM invasion WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_invasion_cleaned = $row['clean'];
   }

   $sql = "select min(clean) clean FROM lures WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_lures_cleaned = $row['clean'];
   }

   $sql = "select min(clean) clean FROM nests WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_nests_cleaned = $row['clean'];
   }

   $sql = "select min(clean) clean FROM gym WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_gyms_cleaned = $row['clean'];
   }


   // Get Areas, Lat, long and Enabled from Humans Table
   $sql = "select area, latitude, longitude, enabled, admin_disable, disabled_date from humans WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $area_set = $row['area'];
       $latitude = $row['latitude'];
       $longitude = $row['longitude'];
       $enabled = $row['enabled'];
       $admin_disable = $row['admin_disable'];
       $disabled_date = $row['disabled_date'];
   }

   // Overwrite with Profile info if a profile is available

   $sql = "select area, latitude, longitude from profiles WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '". $_SESSION['profile'] ."'";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $area_set = $row['area'];
          $latitude = $row['latitude'];
          $longitude = $row['longitude'];
      }
   }

}


if (isset($_SESSION['username'])) {

    // Exit if user not registered to Poracle

    if ( isset($subs_enable) && $subs_enable == 'True' )
    {
            $redirect_page = "subs_renew.php";
    } else {
            $redirect_page = "unregistered.php";
    }

    if (isset($enable_admin_dis) && $enable_admin_dis == "False" && $_SESSION['id'] <> $_SESSION['admin_id'])
    {
	    $subs_clause .= " AND admin_disable = 0";
    }

    $sql = "SELECT * from humans WHERE id = '" . $_SESSION['id'] . "' ".@$subs_clause;
    $result = $conn->query($sql);
    if ($result->num_rows == 0) { 
         if (strpos($_SERVER['REQUEST_URI'],$redirect_page) == false) { 
		 header("Location: $redirect_url/$redirect_page"); 
		 exit();
	 } 
    } 

} else { 
     // If not logged in import login page
     include "./login.php";
     exit();
}

// Add Admin Mode Alarms for Delegated Admin

if (isset($_SESSION['delegated_id']) && $_SESSION['delegated_id'] <> $_SESSION['id'])
{
   $admin_alarm="<div class='alert alert-danger fade show mb-1' role='alert' style='background-color:darkred; color:white;'>";
   $admin_alarm.="<b>".i8ln("ADMIN MODE ACTIVE")."</b><br>";
   $admin_alarm.="<span class='badge badge-light m-1'>".strtoupper($_SESSION['type'])."</span>";
   $admin_alarm.="<span class='badge badge-light m-1'>".strtoupper($_SESSION['username'])."</span><br>";
   if ($_SESSION['delegated_dbname'] <> $_SESSION['dbname'])
   {
           $admin_alarm.="DB : <b>".$_SESSION['dbname']."</b><br>";
   }
   $admin_alarm.="</div>";
   $admin_mode = "True";
}

// Add Admin Mode Alarms for admin_id

if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] <> $_SESSION['id'])
{
   $admin_alarm="<div class='alert alert-danger fade show mb-1' role='alert' style='background-color:darkred; color:white;'>";
   $admin_alarm.="<b>".i8ln("ADMIN MODE ACTIVE")."</b><br>";
   $admin_alarm.="<span class='badge badge-light m-1'>".strtoupper($_SESSION['type'])."</span>";
   $admin_alarm.="<span class='badge badge-light m-1'>".strtoupper($_SESSION['username'])."</span><br>";
   if ($_SESSION['admin_dbname'] <> $_SESSION['dbname'])
   {
           $admin_alarm.="DB : <b>".$_SESSION['dbname']."</b><br>";
   }
   $admin_alarm.="</div>";
   $admin_mode = "True";

   if ($_SESSION['type'] == "discord:channel" || $_SESSION['type'] == "telegram:channel" || $_SESSION['type'] == "telegram:group" || $_SESSION['type'] == "webhook" ) {
           $admin_alarm.="<a href='./admin_sync.php'>";
           $admin_alarm.="<button type='button' class='btn mb-2' style='width:100%; background-color:darkred; color:white;'>";
           $admin_alarm.=i8ln("Synchronize Other Channels with this one");
           $admin_alarm.="</button>";
           $admin_alarm.="</a>";

   }
}

// Check if IV + PvP is used

$sql = "SELECT * FROM monsters
	WHERE (min_iv > 0 or max_iv < 100 or atk > 0 or def > 0 or sta > 0 or max_atk < 15 or max_def < 15 or max_sta < 15) 
	AND pvp_ranking_league <> 0
        AND id = '" . $_SESSION['id'] . "'";

$result = $conn->query($sql);

if (!empty($result) && $result->num_rows > 0) {
   $config_alarm="<div class='alert alert-danger fade show mb-2' role='alert' style='background-color:darkred; color:white;'>";
   $config_alarm.="&#x26A0;<br>";
   $config_alarm.=i8ln("Some of your alarms contains both IV and PvP Filters").".<br>";
   $config_alarm.=i8ln("Alarms will only be triggered if ALL Filters are met").".<br>";
   $config_alarm.="</div>";
}

// Check If Distance Map should be displayed

// Get Map Image URL from API

   $opts = array(
     'http'=>array(
       'method'=>"GET",
       'header'=>"Accept-language: en\r\n" .
                 "X-Poracle-Secret: $api_secret\r\n"
     )
   );

   $context = stream_context_create($opts);

   $config = @file_get_contents("$api_address/api/geofence/distanceMap/$latitude/$longitude/".$row['distance'], false, $context);
   $json = json_decode($config, true);

   if ( $json['status']="ok" && $latitude != "0.0000000000" && $longitude != "0.0000000000" ) { $distance_map = "True"; } else { $distance_map = "False"; } 



