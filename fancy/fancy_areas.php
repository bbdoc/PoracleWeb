
<?php

    // Check Current Selection

    $sql = "select area FROM humans WHERE id = '".$_SESSION['id']."'";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) { $existing_area = $row['area']; }

    // Add Hidden Fancy Box Area Selection

    echo "
    <div style='display: none; max-width:90%;' id='areas'>
    <form action='./form_action.php' method='POST'>
    ";

    echo "<center>";
    echo "<ul>\n";

    $areas=get_areas();
    sort($areas);
    foreach($areas as $key => $area) {

       $area = str_replace(' ', '_', $area);
       if ( stristr($existing_area, $area) > '') { $checked = 'checked'; } else { $checked = ''; };

       echo "<li><input type='checkbox' name='area_$area' id='area_$area' $checked/>\n";
       echo "<label for='area_$area' style='width:200px;'>$area</label>\n";
       echo "</li>\n";
    } 

    echo "</ul>\n";

    echo "

	<center><br>
        <input type='hidden' id='type' name='action' value='areas'> 
        <input type='submit' name='update' value='Update' class='button_update'>
        </center>


    </form>
    </div>
    ";

?>
