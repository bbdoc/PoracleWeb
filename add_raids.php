
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POracle Configurator</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">
  <link rel="stylesheet" type="text/css" href="css/add_style.css?v=<?=time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
</head>
<body style="background-color:#FFFFFF; color: grey;">
<br>

<?php 

include "./config.php";
include "./db_connect.php";

echo "<center>";
echo "<p><b><font color='darkblue' size=4>Welcome ".$_SESSION['username']."</font></b></p><br>";
echo "<font color='darkred'><b>ADD MONSTERS TO YOUR ALARMS</font></b><br><br>";
echo "<form action='./form_action.php' method='POST'>";
echo "<p>Set Parameters you want to use for those new alarms</p><br>";

echo "
        <table width=90% style='max-width: 500px;'>

        <tr><td>
        <label for='fname'>Distance:</label>
        </td><td>
        <input type='number' id='distance' name='distance' value='0' style='width:3em'><br>
        </td></tr>

	</table>
	<br>
";

echo "Select Egg Levels you want to add to your alarms";

echo "<ul>";

  $eggs = explode(',', "1,2,3,4,5,6");
  foreach ($eggs as &$egg) {
     echo "<li><input type='checkbox' name='egg_$egg' id='egg_$egg' />";
     echo "<label for='egg_$egg'><img src='$imgUrl/egg".$egg.".png' />";
     echo "<br>";
     echo "<font size=2>Eggs<br>Level ".$egg."</font></label>";
     echo "</li>";
  }

echo "</ul>";

echo "Select Raid Levels you want to add to your alarms";

echo "<ul>";

  $raids = explode(',', "1,2,3,4,5,6");
  foreach ($raids as &$raid) {
     echo "<li><input type='checkbox' name='raid_$raid' id='raid_$raid' />";
     echo "<label for='raid_$raid'><img src='$imgUrl/egg".$raid.".png' />";
     echo "<br>";
     echo "<font size=2>Raids<br>Level ".$raid."</font></label>";
     echo "</li>";
  }


echo "</ul>";

echo "Select the Raid Bosses you want to add to your alarms";

echo "<ul>";

  $bosses = explode(',', $raid_bosses); 
  foreach ($bosses as &$boss) {
     echo "<li><input type='checkbox' name='mon_$boss' id='mon_$boss' />";
     echo "<label for='mon_$boss'><img src='$imgUrl/pokemon_icon_".str_pad($boss, 3, "0", STR_PAD_LEFT)."_00.png' />";
     echo "<br>";
     echo "<font size=2>".str_pad($boss, 3, "0", STR_PAD_LEFT)."</font></label>";
     echo "</li>";
  }

echo "</ul>";

echo "<input type='submit' name='add_raid' value='Submit' class='button_update' style='width:150px;'>";

echo "</form>";

echo "<a href='$redirect_url'><button class='button_other' style='width:150px;'>Cancel</button></a>";

echo "</center>";
echo "<br><br>";

?>
</table>
