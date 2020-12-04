
<?php

    // Add Hidden Fancy Box Content for Eggs

    echo "
    <div style='display: none;' id='raid_".$row['level']."'>
    <form action='./form_action.php' method='POST'>
    ";

    echo "<center>";
    
    if ( $row['level'] == "9000") {
       echo "<img width=100 src='$imgUrl/pokemon_icon_".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."_00.png'><br>";
    } else {
       echo "<img width=100 src='$imgUrl/egg".$row['level'].".png'><br>";
       echo "<font size=1>Raids Level ".$row['level']."</font>";
    }
    echo "</center>";

    echo "

        <br>
        <input type='hidden' id='type' name='type' value='raids'>
        <input type='hidden' id='pokemon_id' name='level' value='".$row['level']."'>
        <input type='hidden' id='pokemon_id' name='pokemon_id' value='".$row['pokemon_id']."'>

	<table width=130% style='margin-left:-30px;'>

        <tr><td>
	<label for='fname'>Distance:</label>
        </td><td>
	<input type='number' id='distance' name='distance' value='".$row['distance']."' style='width:5em' min='0'><br>
        </td></tr>

        </table>

        <center><br>
	<input type='submit' name='update' value='Update' class='button_update'>
	<input type='submit' name='delete' value='Delete' class='button_delete'>
        </center>

    </form>
    </div>
    ";

?>
