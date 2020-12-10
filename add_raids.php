
<?php 

include "./header.php";

// Add Hidden Fancy Box Profile
include "./fancy/fancy_profile.php";

echo "<center><br>";
echo "<font color='darkred'><b>ADD EGGS / RAIDS / RAIDBOSSES to YOUR ALARMS</font></b><br><br>";
echo "<form action='./form_action.php' method='POST'>";
echo "<p>Set Parameters you want to use for those new alarms</p><br>";

echo "
        <table width=90% style='max-width: 500px;'>

	<tr><td>
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>".$tt_distance."</span></div>
        <label for='fname'>Distance:</label>
        </td><td>
        <input type='number' id='distance' name='distance' value='0' style='width:3em'>&nbsp;meters<br>
        </td></tr>

        <tr><td>";
        if ($row['clean'] == 0) { $checked0 = 'checked'; } else { $checked0 = ''; }
        if ($row['clean'] == 1) { $checked1 = 'checked'; } else { $checked1 = ''; }
        $clean_0_checked = 0;
        $clean_1_checked = 0;
        if ($all_raid_cleaned == "1") { $clean_1_checked = 'checked'; } else { $clean_0_checked = 'checked'; }

        echo "
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>$tt_clean_pkmn</span></div>
	<label for='fname'>Clean:</label>
        </td><td style='max-width: 180px;'>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_0' value='clean_0' $clean_0_checked />
        <label for='clean_0'>No</label>
        </div>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_1' value='clean_1' $clean_1_checked />
        <label for='clean_1'>Yes</label>
        </div>
        </td></tr>

	</table>
	<br>
";

echo "Select Egg Levels you want to add to your alarms";

echo "<ul>";

  $eggs = explode(',', "1,3,5,6");
  foreach ($eggs as &$egg) {
     echo "<li><input type='checkbox' name='egg_$egg' id='egg_$egg' />\n";
     echo "<label for='egg_$egg'><img src='$imgUrl/egg".$egg.".png' />\n";
     echo "<br>\n";
     echo "<font size=2>Eggs<br>Level ".$egg."</font></label>\n";
     echo "</li>\n";
  }

echo "</ul>";

echo "Select Raid Levels you want to add to your alarms";

echo "<ul>";

  $raids = explode(',', "1,3,5,6");
  foreach ($raids as &$raid) {
     echo "<li><input type='checkbox' name='raid_$raid' id='raid_$raid' />\n";
     echo "<label for='raid_$raid'><img src='$imgUrl/egg".$raid.".png' />\n";
     echo "<br>\n";
     echo "<font size=2>Raids<br>Level ".$raid."</font></label>\n";
     echo "</li>\n";
  }


echo "</ul>";

echo "Select the Raid Bosses you want to add to your alarms";

echo "<ul>";

  #$bosses = explode(',', $raid_bosses); 
  #foreach ($bosses as &$boss) {
  $bosses = get_raid_bosses();
  foreach($bosses as $key => $boss) {
     $arr = explode("_", $boss);
     $boss_id = $arr[0];
     $boss_form = $arr[1];
     $boss_mega = $arr[2]; 
     if ($boss_mega == 2) { $mega_name = "Mega X"; } else if ($boss_mega == 3) { $mega_name = "Mega Y"; } else { $mega_name = ""; }
     $pokemon_name=get_mons($boss_id);
     echo "<li><input type='checkbox' name='mon_${boss_id}_${boss_form}' id='mon_${boss_id}_${boss_form}' />\n";
     echo "<label for='mon_${boss_id}_${boss_form}'><img src='$imgUrl/pokemon_icon_".$boss.".png' />\n";
     echo "<br>\n";
     echo "<font size=2>".str_pad($boss_id, 3, "0", STR_PAD_LEFT)."<br>".$pokemon_name."<br>".$mega_name."</font></label>\n";
     echo "</li>\n";
  }

echo "</ul>";

echo "<input type='submit' name='add_raid' value='Submit' class='button_update' style='width:150px;'>";

echo "</form>";

echo "<a href='$redirect_url'><button class='button_other' style='width:150px;'>Cancel</button></a>";

echo "</center>";
echo "<br><br>";

?>
</table>
