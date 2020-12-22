
<?php 

include "./header.php";

// Add Hidden Fancy Box Profile
include "./fancy/fancy_profile.php";

echo "<center><br>";
echo "<font color='darkred'><b>ADD QUESTS POKEMONS / ITEMS to YOUR ALARMS</font></b><br><br>";
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
        if ($all_quest_cleaned == "1") { $clean_1_checked = 'checked'; } else { $clean_0_checked = 'checked'; }

        echo "
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>$tt_clean_quest</span></div>
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

echo "Select Quest Pokemons you want to add to your alarms";

echo "<ul>";

  $mons =  get_quest_mons();
  foreach ($mons as &$mon) {
     $pokemon_name=get_mons($mon);  
     $mon_id=str_pad($mon, 3, "0", STR_PAD_LEFT);
     echo "<li><input type='checkbox' name='mon_$mon' id='mon_$mon' />\n";
     echo "<label for='mon_$mon'><img src='$imgUrl/pokemon_icon_".$mon_id."_00.png' />\n";
     echo "<br>\n";
     echo "<font size=2>".$mon_id."</font><br>\n";
     echo "<font size=2>".$pokemon_name."</font>\n";
     echo "</label>\n";
     echo "</li>\n";
  }

echo "</ul>";

echo "Select Quest Items you want to add to your alarms";

echo "<ul>";

  $items =  get_quest_items();
  foreach ($items as &$item) {
     echo "<li><input type='checkbox' name='item_$item' id='item_$item' />\n";
     echo "<label for='item_$item'><img src='$imgUrl/rewards/reward_".$item."_1.png' />\n";
     echo "</label>\n";
     echo "</li>\n";
  }

echo "</ul>";

echo "<input type='submit' name='add_quest' value='Submit' class='button_update' style='width:150px;'>";

echo "</form>";

echo "<a href='$redirect_url'><button class='button_other' style='width:150px;'>Cancel</button></a>";

echo "</center>";
echo "<br><br>";

?>
</table>
