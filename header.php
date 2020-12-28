<?php

include "./config.php";
include "./db_connect.php";
include "./functions.php";

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

$avatar = "https://cdn.discordapp.com/avatars/" . $_SESSION['id'] . "/" . $_SESSION['avatar'] . ".png";

// Check for Cleaned

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

$sql = "select area, latitude, longitude, enabled from humans WHERE id = '" . $_SESSION['id'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $area = $row['area'];
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
    $enabled = $row['enabled'];
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


