<?php

echo "
    <form action='./form_action.php' method='POST'>
    ";

echo "<div class='text-center mt-3'>";
echo "<img width=100 src='$imgUrl/egg" . $row['level'] . ".png'><br>";
echo "<div class='h5 mb-0 font-weight-bold text-gray-800 text-center mt-2'>
        Eggs level " . $row['level'] . "</div>";
echo "</div>";

?>

<div class="modal-body">

    <?php

        echo "
        <input type='hidden' id='type' name='type' value='eggs'>
        <input type='hidden' id='level' name='level' value='" . $row['level'] . "'>
        <input type='hidden' id='cur_distance' name='cur_distance' value='" . $row['distance'] . "'>
        <input type='hidden' id='cur_team' name='cur_team' value='" . $row['team'] . "'>
        ";
        ?>

    <div class="form-row align-items-center">
        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Distance</div>
                </div>
                <input type="number" id='distance' name='distance' value='<?php echo $row['distance'] ?>' min='0'
                    class="form-control text-center">
                <div class="input-group-append">
                    <span class="input-group-text">m</span>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">Clear</div>
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
            <input type="radio" name="clean" id="clean_0" value="clean_0" <?php echo $checked0; ?>> No
        </label>
        <label class="btn btn-secondary">
            <input type="radio" name="clean" id="clean_1" value="clean_1" <?php echo $checked1; ?>> Yes
        </label>
    </div>

</div>
<div class="modal-footer">
    <button class="btn btn-danger" type="submit" name='delete' value='Delete'>
        <span class="icon text-white-50">
            <i class="fas fa-trash"></i>
        </span>
    </button>
    <input class="btn btn-primary" type='submit' name='update' value='Update'>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
</div>

</form>