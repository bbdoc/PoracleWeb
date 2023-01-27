<?php

include_once "uicons_repo.php";

if(session_status() == PHP_SESSION_NONE){
   session_start();
}

if (!isset($_SESSION['dbname'])) 
{ 
   $dbnames = explode(",", $dbname);
   $_SESSION['dbname'] = $dbnames[0];
} 

if ( !isset($conn) )
{
   $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $_SESSION['dbname']);
   if ($conn->connect_errno) {
      echo "Failed to connect to MySQL: " . $conn->connect_error;
      exit();
   }
}

// Get DB Settings into variables

$sql = "SHOW TABLES LIKE 'pweb_settings'";
$result = $conn->query($sql);

if ($result->num_rows <> 0) {

   $sql = "select * FROM pweb_settings";
   $result = $conn->query($sql);

   while ($row = $result->fetch_assoc()) {
	   ${$row['setting']} = $row['value'];
	   if ( ${$row['setting']}  == "") { unset( ${$row['setting']} ); } 
   }

}

// Set Default UICONS Repo

if (!isset($uicons_pkmn)) { $uicons_pkmn = "https://raw.githubusercontent.com/whitewillem/PogoAssets/main/uicons"; }
if (!isset($uicons_raid)) { $uicons_raid = "https://raw.githubusercontent.com/whitewillem/PogoAssets/main/uicons"; }
if (!isset($uicons_gym)) { $uicons_gym = "https://raw.githubusercontent.com/whitewillem/PogoAssets/main/uicons"; }
if (!isset($uicons_reward)) { $uicons_reward = "https://raw.githubusercontent.com/whitewillem/PogoAssets/main/uicons"; }
