
<?php

    // Add Hidden Fancy Box Content for Eggs

    echo "
    <div style='display: none;' id='egg_".$row['level']."'>
    <form action='./form_action.php' method='POST'>
    ";

    echo "<center>";
    echo "<img width=100 src='$imgUrl/egg".$row['level'].".png'><br>";
    echo "<font size=1>Eggs Level ".$row['level']."</font>";
    echo "</center>";

    echo "

        <br>
        <input type='hidden' id='type' name='type' value='eggs'>
        <input type='hidden' id='pokemon_id' name='level' value='".$row['level']."'>

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
