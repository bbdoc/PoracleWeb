<?php

echo "
    <form action='./actions/invasions.php' method='POST'>
    ";

echo "<div class='text-center mt-3'>";
if ($row['grunt_type'] == "everything") {
	echo "<font style='font-size:24px;'>".i8ln("ALL")."</font><br>";
} else if ( $row['grunt_type'] == "mixed" && $row['gender'] == "0") {
        echo "<img width=100 loading=lazy src='./grunts/James.png?'>"; 
        echo "<img width=100 loading=lazy src='./grunts/Jessie.png?'>";
	echo "<center><font size=5>".ucfirst(i8ln($row['grunt_type']))."</font></center>";
} else if ( $row['grunt_type'] == "mixed" && $row['gender'] == "1") {
        echo "<img width=100 loading=lazy src='./grunts/James.png?'>";
	echo "<center><font size=5>".ucfirst(i8ln($row['grunt_type']))."</font></center>";
} else if ( $row['grunt_type'] == "mixed" && $row['gender'] == "2") {
        echo "<img width=100 loading=lazy src='./grunts/Jessie.png?'>";
	echo "<center><font size=5>".ucfirst(i8ln($row['grunt_type']))."</font></center>";
} else {
	echo "<img width=100 src='./grunts/" . $row['grunt_type'] . ".png?'><br>";
	echo "<center><font size=5>".ucfirst(i8ln($row['grunt_type']))."</font></center>";
}
echo "</div>";

?>

<div class="modal-body">

    <?php

        echo "
        <input type='hidden' id='type' name='type' value='invasions'>
        <input type='hidden' id='uid' name='uid' value='" . $row['uid'] . "'>
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
                <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
                <label class="btn btn-secondary">
		    <input type="radio" name="use_areas_invasion" id="use_areas_<?php echo $invasion_unique_id; ?>" value="areas" <?php echo $area_check; ?> 
                    onclick="areas('<?php echo $invasion_unique_id; ?>')">
                    <?php echo i8ln("Use Areas"); ?>
                </label>
                <label class="btn btn-secondary mr-2">
		    <input type="radio" name="use_areas_invasion" id="use_areas_<?php echo $invasion_unique_id; ?>" value="distance" <?php echo $distance_check; ?> 
                    onclick="areas('<?php echo $invasion_unique_id; ?>')">
                    <?php echo i8ln("Set Distance"); ?>
                </label>
                </div>
            </div>
            <div class="input-group mt-2">
                <input type="number" id='distance_<?php echo $invasion_unique_id; ?>' name='distance' value='<?php echo $row['distance'] ?>' <?php echo $style; ?>
                    min='0' max='<?php echo $_SESSION['maxDistance']; ?>' class="form-control text-center">
                <div class="input-group-append" id="distance_label_<?php echo $invasion_unique_id; ?>" <?php echo $style; ?>>
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
                <div class="input-group-text"><?php echo i8ln("Gender"); ?></div>
            </div>
        </div>
        <?php
                        if ($row['gender'] == 0) {
                                $checked0 = 'checked';
                        } else {
                                $checked0 = '';
                        }
                        if ($row['gender'] == 1) {
                                $checked1 = 'checked';
                        } else {
                                $checked1 = '';
                        }
                        if ($row['gender'] == 2) {
                                $checked2 = 'checked';
                        } else {
                                $checked2 = '';
                        }
                        ?>
        <label class="btn btn-secondary">
            <input type="radio" name="gender" id="gender_0" value="gender_0" <?php echo $checked0; ?>> <?php echo i8ln("All"); ?>
        </label>
        <label class="btn btn-secondary">
            <input type="radio" name="gender" id="gender_1" value="gender_1" <?php echo $checked1; ?>> <?php echo i8ln("Male"); ?>
        </label>
        <label class="btn btn-secondary">
            <input type="radio" name="gender" id="gender_2" value="gender_2" <?php echo $checked2; ?>> <?php echo i8ln("Female"); ?>
        </label>
    </div>

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
