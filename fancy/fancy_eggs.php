
<?php

    // Add Hidden Fancy Box Content for Eggs

    echo "
    <div style='display: none;' id='egg_".$egg_unique_id."'>
    <form action='./form_action.php' method='POST'>
    ";

    echo "<center>";
    echo "<img width=100 src='$imgUrl/egg".$row['level'].".png'><br>";
    echo "<font size=1>Eggs Level ".$row['level']."</font>";
    echo "</center>";

    echo "

        <br>
        <input type='hidden' id='type' name='type' value='eggs'>
	<input type='hidden' id='level' name='level' value='".$row['level']."'>
        <input type='hidden' id='cur_distance' name='cur_distance' value='".$row['distance']."'>
        <input type='hidden' id='cur_team' name='cur_team' value='".$row['team']."'>

	<table width=130% style='margin-left:-30px;'>

        <tr><td>
	<label for='fname'>Distance:</label>
        </td><td>
	<input type='number' id='distance' name='distance' value='".$row['distance']."' style='width:5em' min='0'><br>
        </td></tr>

        <tr><td>";
        if ($row['clean'] == 0) { $checked0 = 'checked'; } else { $checked0 = ''; }
        if ($row['clean'] == 1) { $checked1 = 'checked'; } else { $checked1 = ''; }
        echo "
        <div class='tooltip'><i class='fa fa-question-circle' style='color:darkgreen;'></i><span class='tooltiptext'>".$tt_clean_raid."</span></div>
	<label for='fname'>Clean</label>
        </td><td style='max-width: 180px;'>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_0' value='clean_0' $checked0 />
        <label for='clean_0'>No</label>
        </div>
        <div style='display:inline-block;'>
        <input type='radio' name='clean' id='clean_1' value='clean_1' $checked1 />
        <label for='clean_1'>Yes</label>
        </div>
        </td></tr>

        </table>

        <center><br>
	<input type='submit' name='update' value='Update' class='button_update'>
	<input type='submit' name='delete' value='Delete' class='button_delete'>
        </center>

    </form>
    </div>
    ";


