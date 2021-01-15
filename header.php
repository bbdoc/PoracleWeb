<?php

include "./config.php";
include "./db_connect.php";
include "./functions.php";

set_locale();

if (isset($scan_dbtype) && $scan_dbtype == "MAD") {
    include "./db_mad.php";
}
if (isset($scan_dbtype) && $scan_dbtype == "RDM") {
    include "./db_rdm.php";
}

if (isset($custom_title)) {
    $title = $custom_title;
} else {
    $title = "PoracleWeb";
}

if (false === @file_get_contents($_SESSION['avatar'], 0, null, 0, 1)) {
	$avatar = "$redirect_url/img/no_avatar.png";
} else {
	$avatar = $_SESSION['avatar'];
}

// Check for Cleaned

if (isset($_SESSION['id'])) {

   $sql = "select min(clean) clean FROM monsters WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_mon_cleaned = $row['clean'];
   }
   if ($all_mon_cleaned == "1") {
       $all_mon_cleaned_color = "<span class='greendot'></span>";
   } else {
       $all_mon_cleaned_color = "<span class='reddot'></span>";
   }
   
   $sql = "select min(clean) clean FROM (select id, clean from raid UNION select id, clean from egg) raidegg WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_raid_cleaned = $row['clean'];
   }
   if ($all_raid_cleaned == "1") {
       $all_raid_cleaned_color = "<span class='greendot'></span>";
   } else {
       $all_raid_cleaned_color = "<span class='reddot'></span>";
   }
   
   $sql = "select min(clean) clean FROM quest WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_quest_cleaned = $row['clean'];
   }
   if ($all_quest_cleaned == "1") {
       $all_quest_cleaned_color = "<span class='greendot'></span>";
   } else {
       $all_quest_cleaned_color = "<span class='reddot'></span>";
   }

   $sql = "select min(clean) clean FROM invasion WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $all_invasion_cleaned = $row['clean'];
   }
   if ($all_invasion_cleaned == "1") {
       $all_invasion_cleaned_color = "<span class='greendot'></span>";
   } else {
       $all_invasion_cleaned_color = "<span class='reddot'></span>";
   }


   $sql = "select area, latitude, longitude, enabled from humans WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $area = $row['area'];
       $latitude = $row['latitude'];
       $longitude = $row['longitude'];
       $enabled = $row['enabled'];
   }

}

if (isset($_SESSION['username'])) {
    // Exit if user not registered to Poracle

    $sql = "SELECT * from humans WHERE id = '" . $_SESSION['id'] . "' ".@$subs_clause;
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {

        // Not-Registered Page
        if ( isset($subs_enable) && $subs_enable == 'True' ) { 
	    include "./subs_renew.php"; 
            exit();
	} else {
	    include "./unregistered.php";
	    exit();
	}
    }
} else {
    // If not logged in import login page
    include "./login.php";
    exit();
}

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

   if ($_SESSION['type'] == "discord:channel" || $_SESSION['type'] == "telegram:channel" || $_SESSION['type'] == "telegram:group" ) {
           $admin_alarm.="<a href='./admin_sync.php'>";
           $admin_alarm.="<button type='button' class='btn mb-2' style='width:100%; background-color:darkred; color:white;'>";
           $admin_alarm.=i8ln("Synchronize Other Channels with this one");
           $admin_alarm.="</button>";
           $admin_alarm.="</a>";

   } 
}

// Check I Distance Map should be displayed


if (isset($mapPoracleWeb)) {
   if ($latitude == "0.0000000000" && $longitude == "0.0000000000")
   { 
	   $distance_map = "False"; 
   }
   else { 
	   $distance_map = "True"; 
   }
}



