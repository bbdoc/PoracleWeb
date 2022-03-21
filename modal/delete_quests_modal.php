<div class="modal-header">
    <h5 class="modal-title" id="<?php echo $unique_id ?>DeleteModalTitle">
        <?php echo $delete_title; ?>
        <?php echo $pokemon_name; ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php echo $delete_title; ?>
    <?php echo $pokemon_name; ?><br>
    <?php echo i8ln("Are you sure?"); ?>
</div>
<div class="modal-footer">
    <form action='./actions/quests.php' method='POST'>
        <?php echo "
            <input type='hidden' id='type' name='type' value='quests'>
            <input type='hidden' id='uid' name='uid' value='".$row['uid']."'>
            " ?>
        <input class="btn btn-danger" type='submit' name='delete'
            value='<?php echo i8ln("DELETE"); ?>'>
    </form>
    <button type="button" class="btn btn-secondary"
        data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
</div>

