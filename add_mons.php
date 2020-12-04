
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POracle Configurator</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="style.css?v=<?=time();?>">
  <link rel="stylesheet" type="text/css" href="add_style.css?v=<?=time();?>">
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
        <input type='number' id='distance' name='distance' value='0' style='width:7em'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>IV</label>
        </td><td>
        <label for='fname'>Min</label>
        <input type='number' id='min_iv' name='min_iv' size=1 value='-1' min='-1' max='100'>
        <label for='fname'>Max</label>
        <input type='number' id='max_iv' name='max_iv' size=1 value='100' min='-1' max='100'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>CP</label>
        </td><td>
        <label for='fname'>Min</label>
        <input type='number' id='min_cp' name='min_cp' size=1 value='0' min='0' max='9000'>
        <label for='fname'>Max</label>
        <input type='number' id='max_cp' name='max_cp' size=1 value='9000' min='0' max='9000'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>Level</label>
        </td><td>
        <label for='fname'>Min</label>
        <input type='number' id='min_level' name='min_level' size=1 value='0' min='0' max='50' >
        <label for='fname'>Max</label>
        <input type='number' id='max_level' name='max_level' size=1 value='40' min='0' max='50'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>Weight</label>
        </td><td>
        <label for='fname'>Min</label>
        <input type='number' id='min_weight' name='min_weight' size=2 value='0' min='0' max='9000000'>
        <label for='fname'>Max</label>
        <input type='number' id='max_weight' name='max_weight' size=4 value='9000000' style='width:5em'  min='0' max='9000000'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>Stats</label>
        </td><td>
        <label for='fname'>Min</label>
        <label for='fname'>Atk</label>
        <input type='number' id='atk' name='atk' size=1 value='0' style='width:2em' min='0' max='15'>
        <label for='fname'>Def</label>
        <input type='number' id='def' name='def' size=1 value='0' style='width:2em' min='0' max='15'>
        <label for='fname'>Sta</label>
	<input type='number' id='sta' name='sta' size=1 value='0' style='width:2em' min='0' max='15'><br>
        <label for='fname'>Max</label>
        <label for='fname'>Atk</label>
        <input type='number' id='max_atk' name='max_atk' size=1 value='15' style='width:2em' min='0' max='15'>
        <label for='fname'>Def</label>
        <input type='number' id='max_def' name='max_def' size=1 value='15' style='width:2em' min='0' max='15'>
        <label for='fname'>Sta</label>
        <input type='number' id='max_sta' name='max_sta' size=1 value='15' style='width:2em' min='0' max='15'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>PvP Great</label>
        </td><td>
        <label for='fname'>Rank</label>
        <input type='number' id=great_league_ranking'' name='great_league_ranking' size=1 value='4096' min='0' max='4096'>
        <label for='fname'>CP</label>
        <input type='number' id='great_league_ranking_min_cp' name='great_league_ranking_min_cp' size=1 value='0' min='0' max='4096'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>PvP Ultra</label>
        </td><td>
        <label for='fname'>Rank</label>
        <input type='number' id=ultra_league_ranking'' name='ultra_league_ranking' size=1 value='4096' min='0' max='4096'>
        <label for='fname'>CP</label>
        <input type='number' id='ultra_league_ranking_min_cp' name='ultra_league_ranking_min_cp' size=1 value='0' min='0' max='4096'><br>
        </td></tr>

	</table>
	<br>
";

echo "Select the monsters you want to add to your alarms";

echo "<ul>";


  $sql = "SELECT * from seq_1_to_$max_pokemon WHERE seq not in (select pokemon_id FROM monsters WHERE id = '".$_SESSION['id']."' ORDER BY pokemon_id)";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

     $i=$row['seq'];
     echo "<li><input type='checkbox' name='mon_$i' id='mon_$i' />";
     echo "<label for='mon_$i'><img src='$imgUrl/pokemon_icon_".str_pad($i, 3, "0", STR_PAD_LEFT)."_00.png' />";
     echo "<br>";
     echo "<font size=2>".str_pad($i, 3, "0", STR_PAD_LEFT)."</font></label>";
     echo "</li>";

}

echo "</ul>";

echo "<input type='submit' name='add_mon' value='Submit' class='button_update' style='width:150px;'>";

echo "</form>";

echo "<a href='$redirect_url'><button class='button_other' style='width:150px;'>Cancel</button></a>";

echo "</center>";
echo "<br><br>";

?>
</table>
