<?php

echo "
    <form action='./actions/quests.php' method='POST'>
    ";

echo "<div class='text-center mt-3'>";
if ( $row['reward_type'] == "7") {
    echo "<img width=100 src='$imgUrl/pokemon_icon_".str_pad($mon_id, 3, "0", STR_PAD_LEFT)."_00.png'><br>";
 } elseif ( $row['reward_type'] == "2") {   
    echo "<img width=100 src='$imgUrl/rewards/reward_".$row['reward']."_1.png'><br>";
 }
echo "</div>";

?>

<div class="modal-body">

    <?php

        echo "
        <input type='hidden' id='type' name='type' value='quests'>
        <input type='hidden' id='cur_reward' name='cur_reward' value='".$row['reward']."'>
        <input type='hidden' id='cur_reward_type' name='cur_reward_type' value='".$row['reward_type']."'>
        <input type='hidden' id='cur_distance' name='cur_distance' value='".$row['distance']."'>
        ";
        ?>

    <?php if (@$disable_location <> "True") { ?>
    <div class="form-row align-items-center">
        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
		    <div class="input-group-text"><?php echo i8ln("Distance"); ?></div>
                </div>
                <input type="number" id='distance' name='distance' value='<?php echo $row['distance'] ?>' min='0'
                    class="form-control text-center">
                <div class="input-group-append">
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
    <!---
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
