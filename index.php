
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POracle Configurator</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
  <script type="text/javascript">
    function confirm_mon_delete() {
       if (confirm('This will delete all your Pokemon Alarms and cannot be undone, are you sure ?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }
    function confirm_raid_delete() {
       if (confirm('This will delete all your Eggs & Raids Alarms and cannot be undone, are you sure ?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }
    </script>

</head>

<body style="background-color:#FFFFFF; color: grey;">

<?php 

if ( $_GET['return'] == 'success_added_raids' ) { echo "<div class='success_msg'>Successfully Added Raids Alarm(s)</div>"; }
if ( $_GET['return'] == 'success_update_mons' ) { echo "<div class='success_msg'>Successfully Updated Monster Alarm</div>"; }
if ( $_GET['return'] == 'success_update_raid' ) { echo "<div class='success_msg'>Successfully Updated Raid Alarm</div>"; }
if ( $_GET['return'] == 'success_update_egg' ) { echo "<div class='success_msg'>Successfully Updated Egg Alarm</div>"; }
if ( $_GET['return'] == 'success_delete_mons' ) { echo "<div class='success_msg'>Successfully Deleted Monster Alarm(s)</div>"; }
if ( $_GET['return'] == 'success_added_mons' ) { echo "<div class='success_msg'>Successfully Added Monster Alarm(s)</div>"; }
if ( $_GET['return'] == 'success_delete_raids' ) { echo "<div class='success_msg'>Successfully Deleted Eggs & Raids Alarm(s)</div>"; }
if ( $_GET['return'] == 'success_delete_raid' ) { echo "<div class='success_msg'>Successfully Deleted Raid Alarm</div>"; }
if ( $_GET['return'] == 'success_delete_egg' ) { echo "<div class='success_msg'>Successfully Deleted Egg Alarm</div>"; }
if ( $_GET['return'] == 'success_update_areas' ) { echo "<div class='success_msg'>Successfully Updated List of Areas</div>"; }


echo "<br>";

include "./config.php";
include "./db_connect.php";

echo "<center>";

if($_SESSION['username']) {

  echo "<p><b><font color='darkblue' size=4>Welcome ".$_SESSION['username']."</font></b></p>";
  $avatar = "https://cdn.discordapp.com/avatars/".$_SESSION['id']. "/".$_SESSION['avatar'].".png";
  echo "<img src='$avatar' style='border-radius: 50%; width:50px;'><br><br>";
  #echo '<p><a href="./discord_auth.php?action=logout">Log Out</a></p>';

  // Exit if user not registered to Poracle

  $sql = "SELECT * from humans WHERE id = '".$_SESSION['id']."'";
  $result = $conn->query($sql);
  if ( $result->num_rows == 0 ) {
	  echo "<br><font color='darkred'><b>Please Register to Poracle first before using this tool.</font></b><br>";
	  exit();
  }
  
  // Show Global Informations from Humans

  $sql = "select area, latitude, longitude, enabled from humans WHERE id = '".$_SESSION['id']."'";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
        $area=$row['area'];
        $latitude=$row['latitude'];
        $longitude=$row['longitude'];
        $enabled=$row['enabled'];
  }

  if ( $enabled == "1") { 
    echo "<font color='darkgreen'><b>Your alarms are currently enabled</font></b><br>";
    echo "<a href='./form_action.php?action=disable'><button class='button_delete' style='width:150px;'>Disable</button></a><br>";
  } else {
    echo "<font color='darkred'><b>Your alarms are currently disabled</font></b><br>";
    echo "<a href='./form_action.php?action=enable'><button class='button_update' style='width:150px;'>Enable</button></a><br>";
  }

  if ( $latitude == "0.0000000000" && $longitude == "0.0000000000" ) {
	  echo "<font color='darkred'><b>Your Location is not set and cannot be set here.<br>";
	  echo "Please set it in discord using <code>/location</code> command</font></b><br><br>";
  } else if ( isset($mapURL) && $mapURL <> ""  ) {
	  echo "Your Location is currently set to <br>$latitude, $longitude<br><br>";
          $mapURL=str_replace('#LAT#', $latitude, $mapURL);
          $mapURL=str_replace('#LON#', $longitude, $mapURL);
          echo "<img src='$mapURL' width=400><br><br>";
  }

  echo "<hr>";
  if ($area == "[]") { 
          echo "<font color='darkred'><b>You have not set any area yet</font></b><br>";
	  $areas = ""; 
  } else { 
          echo "You are currently receiving alarms for the following area(s) :<br>";
	  $areas = explode(",", $area); 
  }

  echo "<ul>";
  foreach($areas as $key => $area)
  { 
       $area=str_replace('"', '', $area); 
       $area=str_replace('[', '', $area); 
       $area=str_replace(']', '', $area); 
       echo "<li><input type='checkbox' name='area_$area' id='area_$area' checked onclick='return false;'/>";
       echo "<label for='area_$area' style='width:200px;'>".strtoupper($area);
       echo "</li>";
  }
  echo "</ul>";

  // Add Hidden Fancy Boxes
  include "./fancy/fancy_areas.php";

  echo "<a data-fancybox data-src='#areas' href='javascript:;' style='text-decoration: none;'>";
  echo "<button class='button_update' style='width:150px;'>Select Areas</button>";
  echo "</a>";

  // Show Monsters Alarms

  echo "<hr><br><p><b>Monsters you are tracking</b></p>";
  echo "<i>Click on any Alarm to edit your tracking parameters</i></p><br>";
  echo "<a href='./add_mons.php'><button class='button_update' style='width:150px;'>Add New</button></a>";
  echo "<a href='./form_action.php?action=delete_all_mons'><button class='button_delete' style='width:150px;' onclick='return confirm_mon_delete();'>Delete All</button></a>";
  echo "<br><br>";

  $sql = "select * FROM monsters WHERE id = '".$_SESSION['id']."' ORDER BY pokemon_id";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_pokemons.php";

    echo "<a data-fancybox data-src='#mon_".$row['pokemon_id']."' href='javascript:;'>";
    echo "<button>";
    echo "<font size=1>";
    echo "<table width=100%><tr>";
   
    if ( $row['pokemon_id'] == '0' ) {
      echo "<td height=60><font size=5><strong>ALL</strong></font></td>";
    } else {
      echo "<td><img width=50 src='$imgUrl/pokemon_icon_".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."_00.png'></td>";
    }
    echo "<td width=100%>";

    if ($row['distance'] <> '0' ) {
      echo "<p><b>Distance : </b>".$row['distance']."</p>";
    }
    if ($row['min_iv'] <> '-1' || $row['max_iv'] <> '100' ) {
      echo "<p><b>IV : </b>".$row['min_iv']." - ".$row['max_iv']."</p>";
    } else { echo "<b>IV : </b>ALL"; }
    if ($row['min_cp'] <> '0' || $row['max_cp'] <> '9000' ) {
      echo "<p><b>CP : </b>".$row['min_cp']." - ".$row['max_cp']."</p>";
    }
    if ($row['min_level'] <> '0' || $row['max_level'] <> '40' ) {
      echo "<p><b>Level : </b>".$row['min_level']." - ".$row['max_level']."</p>";
    }
    if ($row['atk'] <> '0' || $row['def'] <> '0' || $row['sta'] <> '0' || $row['max_atk'] <> '15' || $row['max_def'] <> '15' || $row['max_sta'] <> '15' ) {
      echo "<p><b>Stats : </b>".$row['atk']."/".$row['def']."/".$row['sta']." - ".$row['max_atk']."/".$row['max_def']."/".$row['max_sta']."</p>";;
    }
    if ($row['great_league_ranking'] <> '4096' || $row['great_league_ranking_min_cp'] <> '0' ) {
      echo "<p><b>Great : </b>top".$row['great_league_ranking']." @".$row['great_league_ranking_min_cp']."</p>";
    }
    if ($row['ultra_league_ranking'] <> '4096') {
      echo "<p><b>Ultra : </b>top".$row['ultra_league_ranking']." @".$row['ultra_league_ranking_min_cp']."</p>";
    }
    if ($row['form'] <> '0' ) {
      echo "<p><b>Form : </b>".$row['form']."</p>";
    }
    if ($row['min_weight'] <> '0' || $row['max_weight'] <> '9000000' ) {
      echo "<p><b>CP : </b>".$row['min_weight']." - ".$row['max_weight']."</p>";
    }
    echo "</td>";
    echo "</tr></table>";

    echo "</font>";
    echo "</button>";
    echo "</a>";

  }

  // Show Eggs & Raids

  echo "<hr><br><p><b>Eggs & Raids you are tracking</b></p>\n";
  echo "<i>Click on any Alarm to edit your tracking parameters</i></p><br>";
  echo "<a href='./add_raids.php'><button class='button_update' style='width:150px;'>Add New</button></a>\n";
  echo "<a href='./form_action.php?action=delete_all_raids'><button class='button_delete' style='width:150px;' onclick='return confirm_raid_delete();'>Delete All</button></a>\n";
  echo "<br><br>\n";

  $sql = "select * FROM egg WHERE id = '".$_SESSION['id']."' ORDER BY level";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_eggs.php";

    echo "<a data-fancybox data-src='#egg_".$row['level']."' href='javascript:;' style='text-decoration: none;'>";
    echo "<button style='width:100px; height:130px;'>\n";
    echo "<img width=50 src='$imgUrl/egg".$row['level'].".png'><br><br>\n";
    echo "<font size=1>";
    echo "Eggs Level ".$row['level'];
    if ($row['distance'] <> '0' ) {
      echo "<br>Distance : ".$row['distance']."<br>";
    }
    echo "</font>\n";
    echo "</button>\n";
    echo "</a>\n";

  }

  $sql = "select * FROM raid WHERE id = '".$_SESSION['id']."' AND pokemon_id = 9000 ORDER BY level";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_raids.php";

    echo "<a data-fancybox class='various' data-src='#raid_".$row['level']."' href='javascript:;' style='text-decoration: none;'>";
    echo "<button style='width:100px; height:130px;'>\n";
    echo "<img width=50 src='$imgUrl/egg".$row['level'].".png'><br><br>\n";
    echo "<font size=1>";
    echo "Raids Level ".$row['level'];
    if ($row['distance'] <> '0' ) {
      echo "<br>Distance : ".$row['distance'];
    }
    echo "</font>\n";
    echo "</button>\n";
    echo "</a>\n";

  }

  $sql = "select * FROM raid WHERE id = '".$_SESSION['id']."' AND pokemon_id <> 9000 ORDER BY pokemon_id";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_raids.php";

    echo "<a data-fancybox class='various' data-src='#raid_".$row['level']."' href='javascript:;' style='text-decoration: none;'>";
    echo "<button style='width:100px; height:130px;'>\n";
    echo "<img width=50 src='$imgUrl/pokemon_icon_".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."_00.png'><br><br>\n";
    echo "<font size=1>";
    if ($row['distance'] <> '0' ) {
      echo "<br>Distance : ".$row['distance'];
    }
    echo "</font>\n";
    echo "</button>\n";
    echo "</a>\n";

  }


} else {
  echo '<h2>Welcome to the <br>Poracle Alarm Management</h2>';
  echo '<h4>Please Log In to access your current Alarm Config</h4>';
  echo '<h4>Clic on below Discord icon to log in</h4>';
  echo '<p><a href="./discord_auth.php?action=login"><img width=100 src="./discord.jpg"></a></p>';
  echo '<br><p>Note that you need a valid registration on the poracle server to get access to this service</p>';
}

echo "<br><br>";
?>

</body>
