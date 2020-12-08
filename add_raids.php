
<?php 

include "./header.php";

// Add Hidden Fancy Box Profile
include "./fancy/fancy_profile.php";

echo "<center><br>";
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

        <tr><td>";
        if ($row['clean'] == 0) { $checked0 = 'checked'; } else { $checked0 = ''; }
        if ($row['clean'] == 1) { $checked1 = 'checked'; } else { $checked1 = ''; }
        echo "
        <label for='fname'>Clean:</label>
        </td><td style='max-width: 180px;'>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_0' value='clean_0' />
        <label for='clean_0'>No</label>
        </div>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_1' value='clean_1' checked />
        <label for='clean_1'>Yes</label>
        </div>
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
