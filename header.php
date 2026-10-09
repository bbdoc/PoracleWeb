<?php

include_once "./config.php";
include_once "./include/db_connect.php";
include_once "./include/functions.php";
include_once "./include/defaults.php";
include_once "./include/poracle_forms.php";

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

ini_set('default_socket_timeout', 1);

if (!isset($_SESSION['avatar']) || false === @file_get_contents($_SESSION['avatar'], 0, null, 0, 1)) {
	$avatar = "$redirect_url/img/no_avatar.png";
} else {
	$avatar = $_SESSION['avatar'];
}

// Load the human, profile and tracking snapshot from the PoracleNG API

$snapshot = null;
if (isset($_SESSION['id'])) {
   try {
      $snapshot = poracle_snapshot();
   } catch (PoracleApiException $e) {
      $snapshot = null;
   }
}
if (!empty($snapshot)) {
   // Set Profile to current if not yet set
   if (!isset($_SESSION['profile'])) {
      $_SESSION['profile'] = (int) $snapshot['human']['current_profile_no'];
   }
   $profile_view = poracle_profile_view($snapshot, $_SESSION['profile']);
   $_SESSION['number_of_profiles'] = $profile_view['profile_count'];
   $_SESSION['profile_name'] = $profile_view['name'] !== null ? $profile_view['name'] : i8ln("Default");
   $_SESSION['current_profile'] = $profile_view['current_profile_no'];

   // Cleaning toggles span every profile, as before
   $all_mon_cleaned = poracle_all_clean(poracle_table_rules('monsters', null));
   $all_raid_cleaned = poracle_all_clean(array_merge(poracle_table_rules('raid', null), poracle_table_rules('egg', null)));
   $all_quest_cleaned = poracle_all_clean(poracle_table_rules('quest', null));
   $all_invasion_cleaned = poracle_all_clean(poracle_table_rules('invasion', null));
   $all_lures_cleaned = poracle_all_clean(poracle_table_rules('lures', null));
   $all_nests_cleaned = poracle_all_clean(poracle_table_rules('nests', null));
   $all_gyms_cleaned = poracle_all_clean(poracle_table_rules('gym', null));

   $area_set = json_encode($profile_view['area']);
   $latitude = $profile_view['latitude'];
   $longitude = $profile_view['longitude'];
   $enabled = $profile_view['enabled'];
   $admin_disable = $profile_view['admin_disable'];
   $disabled_date = $profile_view['disabled_date'];
}

if (isset($_SESSION['username'])) {

    // Exit if user not registered to Poracle

    if ( isset($subs_enable) && $subs_enable == 'True' )
    {
            $redirect_page = "subs_renew.php";
    } else {
            $redirect_page = "unregistered.php";
    }

    if (!isset($subs_clause)) { $subs_clause = ""; }

    $registered = !empty($snapshot);
    if ($registered && isset($enable_admin_dis) && $enable_admin_dis == "False" && isset($_SESSION['admin_id']) && $_SESSION['id'] <> $_SESSION['admin_id'])
    {
	    $registered = ((int) $snapshot['human']['admin_disable'] == 0);
    }
    if (!$registered) {
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

   if ( isset($_SESSION['notes']) ) {
	   $admin_alarm.="<span class='badge badge-light'>".$_SESSION['notes']."</span>";
   }

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

   if ( isset($_SESSION['notes']) ) {
           $admin_alarm.="<span class='badge badge-light'>".$_SESSION['notes']."</span>";
   }

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

$iv_pvp_used = false;
if (!empty($snapshot)) {
   foreach (poracle_rules('pokemon', null) as $rule) {
      if ($rule['pvp_ranking_league'] === null) { continue; }
      $iv_set = ($rule['min_iv'] !== null && $rule['min_iv'] > 0);
      foreach (array('max_iv', 'atk', 'def', 'sta', 'max_atk', 'max_def', 'max_sta') as $f) {
         if (isset($rule[$f])) { $iv_set = true; }
      }
      if ($iv_set) { $iv_pvp_used = true; break; }
   }
}

if ($iv_pvp_used) {
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



