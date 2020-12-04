
<?php

    // Add Hidden Fancy Box Content for Pokemons

    echo "
    <div style='display: none;' id='mon_".$row['pokemon_id']."'>
    <form action='./form_action.php' method='POST'>
    ";

    if ( $row['pokemon_id'] == '0' ) {
      echo "<center><font size=5><strong>ALL</strong></font></center>";
    } else {
      echo "<center><img width=100 src='$imgUrl/pokemon_icon_".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."_00.png'></center>";
    }

    echo "

        <br>
        <input type='hidden' id='type' name='type' value='monsters'>
        <input type='hidden' id='pokemon_id' name='pokemon_id' value='".$row['pokemon_id']."'>

	<table width=130% style='margin-left:-30px;'>

        <tr><td>
	<label for='fname'>Distance:</label>
        </td><td>
	<input type='text' id='distance' name='distance' value='".$row['distance']."' style='width:3em'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>IV</label>
        </td><td>
        <label for='fname'>Min</label>
	<input type='text' id='min_iv' name='min_iv' size=1 value='".$row['min_iv']."'>
        <label for='fname'>Max</label>
	<input type='text' id='max_iv' name='max_iv' size=1 value='".$row['max_iv']."'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>CP</label>
        </td><td>
        <label for='fname'>Min</label>
	<input type='text' id='min_cp' name='min_cp' size=1 value='".$row['min_cp']."'>
        <label for='fname'>Max</label>
	<input type='text' id='max_cp' name='max_cp' size=1 value='".$row['max_cp']."'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>Level</label>
        </td><td>
        <label for='fname'>Min</label>
        <input type='text' id='min_level' name='min_level' size=1 value='".$row['min_level']."'>
        <label for='fname'>Max</label>
        <input type='text' id='max_level' name='max_level' size=1 value='".$row['max_level']."'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>Weight</label>
        </td><td>
        <label for='fname'>Min</label>
        <input type='text' id='min_weight' name='min_weight' size=2 value='".$row['min_weight']."'>
        <label for='fname'>Max</label>
        <input type='text' id='max_weight' name='max_weight' size=4 value='".$row['max_weight']."'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>Stats</label>
        </td><td>
        <label for='fname'>Min</label>
        <label for='fname'>Atk</label>
        <input type='text' id='atk' name='atk' size=1 value='".$row['atk']."' style='width:2em'>
        <label for='fname'>Def</label>
        <input type='text' id='def' name='def' size=1 value='".$row['def']."' style='width:2em'>
        <label for='fname'>Sta</label>
	<input type='text' id='sta' name='sta' size=1 value='".$row['sta']."' style='width:2em'><br>
        <label for='fname'>Max</label>
        <label for='fname'>Atk</label>
        <input type='text' id='max_atk' name='max_atk' size=1 value='".$row['max_atk']."' style='width:2em'>
        <label for='fname'>Def</label>
        <input type='text' id='max_def' name='max_def' size=1 value='".$row['max_def']."' style='width:2em'>
        <label for='fname'>Sta</label>
        <input type='text' id='max_sta' name='max_sta' size=1 value='".$row['max_sta']."' style='width:2em'><br>

        </td></tr>

        <tr><td>
        <label for='fname'>PvP Great</label>
        </td><td>
        <label for='fname'>Rank</label>
        <input type='text' id=great_league_ranking'' name='great_league_ranking' size=1 value='".$row['great_league_ranking']."'>
        <label for='fname'>CP</label>
        <input type='text' id='great_league_ranking_min_cp' name='great_league_ranking_min_cp' size=1 value='".$row['great_league_ranking_min_cp']."'><br>
        </td></tr>

        <tr><td>
        <label for='fname'>PvP Ultra</label>
        </td><td>
        <label for='fname'>Rank</label>
        <input type='text' id=ultra_league_ranking'' name='ultra_league_ranking' size=1 value='".$row['ultra_league_ranking']."'>
        <label for='fname'>CP</label>
        <input type='text' id='ultra_league_ranking_min_cp' name='ultra_league_ranking_min_cp' size=1 value='".$row['ultra_league_ranking_min_cp']."'><br>
        </td></tr>

        </table>

        <center>
	<input type='submit' name='update' value='Update' class='button_update'>
	<input type='submit' name='delete' value='Delete' class='button_delete'>
        </center>

    </form>
    </div>
    ";

?>
