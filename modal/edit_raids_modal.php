<?php

echo "
    <form action='./actions/raids.php' method='POST'>
    ";

echo "<div class='text-center mt-3'>";
if ($row['level'] == "9000") {
        echo "<img width=100 src='$imgUrl/pokemon_icon_" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "_00.png'><br>";
} else {
        echo "<img width=100 src='./img/raid_" . $row['level'] . ".png'><br>";

        echo "<div class='h5 mb-0 font-weight-bold text-gray-800 text-center mt-2'>".
                i8ln("Raids Level")." " . $row['level'] . "</div>";
}
echo "</div>";

?>

<div class="modal-body">

    <?php

        echo "
        <input type='hidden' id='type' name='type' value='raids'>
        <input type='hidden' id='level' name='level' value='" . $row['level'] . "'>
        <input type='hidden' id='pokemon_id' name='pokemon_id' value='" . $row['pokemon_id'] . "'>
        <input type='hidden' id='cur_form' name='cur_form' value='" . $row['form'] . "'>
        <input type='hidden' id='cur_distance' name='cur_distance' value='" . $row['distance'] . "'>
        <input type='hidden' id='cur_team' name='cur_team' value='" . $row['team'] . "'>
        ";
        ?>

    <?php if (@$disable_location <> "True") { ?>
    <div class="form-row align-items-center">
        <div class="col-sm-12 my-1">
            <?php
            if ( $row['distance'] == 0 ) {
               $area_check="checked";
               $distance_check="";
               $style="style='display:none;'";
            } else {
               $area_check="";
               $distance_check="checked";
               $style="";
            }
            ?>
            <div class="input-group">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary">
		    <input type="radio" name="use_areas_raid" id="use_areas_<?php echo $raid_unique_id; ?>" value="areas" <?php echo $area_check; ?> 
                    onclick="areas('<?php echo $raid_unique_id; ?>')">
                    <?php echo i8ln("Areas"); ?>
                </label>
                <label class="btn btn-secondary mr-2">
		    <input type="radio" name="use_areas_raid" id="use_areas_<?php echo $raid_unique_id; ?>" value="distance" <?php echo $distance_check; ?> 
                    onclick="areas('<?php echo $raid_unique_id; ?>')">
                    <?php echo i8ln("Distance"); ?>
                </label>
                </div>
                <input type="number" id='distance_<?php echo $raid_unique_id; ?>' name='distance' value='<?php echo $row['distance'] ?>' <?php echo $style; ?>
                    min='0' class="form-control text-center">
                <div class="input-group-append" id="distance_label_<?php echo $raid_unique_id; ?>" <?php echo $style; ?>>
                    <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <input type="hidden" id='distance' name='distance' value='<?php echo $row['distance'] ?>' min='0'>
    <?php } ?>

    <hr>
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <div class="input-group">
            <div class="input-group-prepend">
		<div class="input-group-text"><?php echo i8ln("Clean"); ?></div>
            </div>
        </div>
        <?php
                if ($row['clean'] == 0) {
                        $checked0 = 'checked';
                } else {
                        $checked0 = '';
                }
                if ($row['clean'] == 1) {
                        $checked1 = 'checked';
                } else {
                        $checked1 = '';
                }
                ?>
        <label class="btn btn-secondary">
	    <input type="radio" name="clean" id="clean_0" value="clean_0" <?php echo $checked0; ?>> <?php echo i8ln("No"); ?>
        </label>
        <label class="btn btn-secondary">
	    <input type="radio" name="clean" id="clean_1" value="clean_1" <?php echo $checked1; ?>> <?php echo i8ln("Yes"); ?>
        </label>
    </div>

</div>
<div class="modal-footer">
    <!--
    <button class="btn btn-danger" type="submit" name='delete' value='Delete'>
        <span class="icon text-white-50">
            <i class="fas fa-trash"></i>
        </span>
    </button>
    -->
    <input class="btn btn-primary" type='submit' name='update' value='<?php echo i8ln("Update"); ?>'>
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo i8ln("Cancel"); ?></button>
</div>

</form>
