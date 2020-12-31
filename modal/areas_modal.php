<?php

// Check Current Selection

$sql = "select area FROM humans WHERE id = '" . $_SESSION['id'] . "'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $existing_area = $row['area'];
    $existing_area = json_decode($row['area']);
}

echo "
    <div id='areas' class='areasform text-uppercase text-center'>
    <form action='./form_action.php' method='POST'>
    ";


echo "<ul>\n";

$areas = get_areas();
sort($areas);
foreach ($areas as $key => $area) {

    $area = str_replace(' ', '_', $area);

    if (in_array(strtolower($area), $existing_area)) {
        $checked = 'checked';
    } else {
        $checked = '';
    };
    echo "<li><input type='checkbox' name='area_$area' id='area_$area' $checked/>\n";
    echo "<label for='area_$area' style='width:160px;'>$area</label>\n";
    echo "</li>\n";
}

echo "</ul>\n";

echo "
        <hr>
        <div class='float-right'>            
            <input type='hidden' id='type' name='action' value='areas'>
	    <button type='submit' name='update' value='Update' class='btn btn-primary'>".i8ln('Update')."</button>
            <button type='button' class='btn btn-secondary' data-dismiss='modal'>".i8ln('Close')."</button>
        </div>
    </form>
    </div>
    ";
