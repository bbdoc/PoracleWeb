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
    <form action='./actions/areas.php' method='POST'>
    ";


echo "</ul>\n";
$areas = get_areas();


$collapsedState = 'collapsed';

// Show no accordions when there is only one list of areas (likely a user with no grouping)
if(count(array_keys($areas)) === 1){
    $collapsedState = '';
    $areaList = $areas[0];
    echo "<ul>\n";
    sort($areaList);
    foreach ($areaList as $i => $area) {
        $area_var = str_replace(' ', '%20', $area);

        if (in_array(strtolower($area), $existing_area)) {
            $checked = 'checked';
        } else {
            $checked = '';
        };
        echo "<li><input type='checkbox' name='area_$area_var' id='area_$area_var' $checked/>\n";
        echo "<label for='area_$area_var' style='width:160px;'><font style='font-size:12px;'>$area</font></label>\n";
        echo "</li>\n";
    }

} else {
    echo "<div class='accordion' id='accordion'>";

    foreach ($areas as $group => $areaList) {
        $updatedGroup = str_replace(' ', '_', $group);

        echo "<div class='card'>
             <div class='card-header' id='heading$updatedGroup'>
                  <h5 class='mb-0'>
                <button class='btn btn-link $collapsedState' type='button' data-toggle='collapse' data-target='#collapse$updatedGroup' aria-expanded='false' aria-controls='collapse$updatedGroup'>
                  $group
                </button>
              </h5>
            </div>
    
            <div id='collapse$updatedGroup' class='collapse' aria-labelledby='heading$updatedGroup' data-parent='#accordion'>
                <div class='card-body'>";
        sort($areaList);
        echo "<ul>\n";
        foreach ($areaList as $i => $area) {
            $area_var = str_replace(' ', '%20', $area);

            if (in_array(strtolower($area), $existing_area)) {
                $checked = 'checked';
            } else {
                $checked = '';
            };
            echo "<li><input type='checkbox' name='area_$area_var' id='area_$area_var' $checked/>\n";
            echo "<label for='area_$area_var' style='width:160px;'><font style='font-size:12px;'>$area</font></label>\n";
            echo "</li>\n";
        }
        echo "</ul>\n</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}
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
