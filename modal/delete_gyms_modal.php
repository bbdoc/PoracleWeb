<div class="modal-header">
    <h5 class="modal-title" id="<?php echo $unique_id ?>DeleteModalTitle">
        <?php echo i8ln("Delete tracking for Gyms"); ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php echo i8ln("This will delete tracking for Gym"); ?> : 
    <?php echo i8ln(get_gym_name($row['team'])); ?><br>
    <?php echo i8ln("Are you sure?"); ?>
</div>
<div class="modal-footer">
    <form action='./actions/gyms.php' method='POST'>
        <?php echo "
            <input type='hidden' id='type' name='type' value='gyms'>
            <input type='hidden' id='uid' name='uid' value='" . $row['uid'] . "'>
            " ?>
        <input class="btn btn-danger" type='submit' name='delete'
            value='<?php echo i8ln("DELETE"); ?>'>
    </form>
    <button type="button" class="btn btn-secondary"
        data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
</div>

