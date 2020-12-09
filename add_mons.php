
<?php 

include "./header.php";

?>

<script type="text/javascript" src="search_mons.js"></script>

<script type="text/javascript">
 $(document).ready(function () {
   $("input[type='checkbox']").change(function () {
      var maxAllowed = 100;
      var cnt = $("input[type='checkbox']:checked").length;
      if (cnt > maxAllowed)
      {
         $(this).prop("checked", "");
         alert('Sorry, you cannot select more than ' + maxAllowed + ' Pokemons at a time!');
     }
  });
 });

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});

</script>

<?php

// Add Hidden Fancy Box Profile
include "./fancy/fancy_profile.php";


echo "<center><br>";
echo "<font color='darkred'><b>ADD MONSTERS TO YOUR ALARMS</font></b><br><br>";
echo "<form action='./form_action.php' method='POST'>";
echo "<p>Set Parameters you want to use for those new alarms</p><br>";

echo "
        <table width=90% style='max-width: 500px;'>

	<tr><td>
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>".$tt_distance."</span></div>
        <label for='fname'>Distance</label>
        </td><td>
        <input type='number' id='distance' name='distance' value='0' style='width:7em'>&nbsp;meters<br>
        </td></tr>

	<tr><td>
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>$tt_iv_pkmn</span></div>
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
        <label for='fname'>Min Rank</label>
        <input type='number' id=great_league_ranking'' name='great_league_ranking' size=1 value='4096' min='0' max='4096'>
        <label for='fname'>Min CP</label>
        <input type='number' id='great_league_ranking_min_cp' name='great_league_ranking_min_cp' size=1 value='0' min='0' max='4096'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>PvP Ultra</label>
        </td><td>
        <label for='fname'>Min Rank</label>
        <input type='number' id=ultra_league_ranking'' name='ultra_league_ranking' size=1 value='4096' min='0' max='4096'>
        <label for='fname'>Min CP</label>
        <input type='number' id='ultra_league_ranking_min_cp' name='ultra_league_ranking_min_cp' size=1 value='0' min='0' max='4096'><br>
        </td></tr>

        </td></tr>
        <tr><td>
        <label for='fname'>Gender</label>
        </td><td style='max-width: 180px;'>
        <div style='display:inline-block;'>
        <input type='radio' name='gender' id='gender_0' value='gender_0' checked/>
        <label for='gender_0'>All</label>
        </div>
        <div style='display:inline-block;'>
        <input type='radio' name='gender' id='gender_1' value='gender_1' />
        <label for='gender_1'>Male</label>
        </div>
        <div style='display:inline-block;'>
        <input type='radio' name='gender' id='gender_2' value='gender_2' />
        <label for='gender_2'>Female</label>
        </div>
	</td></tr>

	<tr><td>
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>$tt_clean_pkmn</span></div>
        <label for='fname'>Clean</label>
	</td><td style='max-width: 180px;'>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_1' value='clean_1' checked />
        <label for='clean_1'>Yes</label>
        </div>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_0' value='clean_0' />
        <label for='clean_0'>No</label>
        </div>
        </td></tr>


	</table>
	<br>
";

echo "<div style='max-width:90%;'>";
echo "Select the monsters you want to add to your alarms<br><br>\n";
echo "Type ALL in search box for displaying all Pokemons Available<br>\n";
echo "</div><br>";

# Add Search Box 
echo "<input type='text' id='search' placeholder='Search' style='width:300px; font-size:20px;'/><br>";

echo "<ul>\n";

echo "<li><input type='checkbox' name='mon_0' id='mon_0' />\n";
echo "<label for='mon_0' style='padding:15px;'><center>";
echo "<font size=4>Apply Settings to all Pokemons</font><br>";
echo "<font size=2>Other selections won't apply if selected</font>";
echo "</center></label>";
echo "</li>\n";
echo "<br>\n";

# Add Empty Div to be used by Ajax to display results
echo "<div id='display'></div>";

echo "</form>";

echo "</ul>";

echo "<input type='submit' name='add_mon' value='Submit' class='button_update' style='width:150px;'><br>";
echo "<a href='$redirect_url'><button class='button_other' style='width:150px;'>Cancel</button></a>";

echo "</center>";
echo "<br><br>";

?>

</table>

