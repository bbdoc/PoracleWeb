<div class="modal-header">
    <h5 class="modal-title" id="<?php echo $pkm_unique_id ?>DeleteModalTitle">
        <?php echo i8ln("Delete tracking for"); ?>
        <?php echo get_mons($row['pokemon_id'])." ".$form; ?>
        <?php if ( $row['pokemon_id'] == 0 ) { echo i8ln("ALL"); } ?> ?
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php echo i8ln("This will delete tracking for"); ?>
    <?php echo get_mons($row['pokemon_id'])." ".$form; ?>
    <?php if ( $row['pokemon_id'] == 0 ) { echo i8ln("ALL"); } ?>.<br>
    <?php echo i8ln("Are you sure?"); ?>
</div>
<div class="modal-footer">
    <form action='./actions/monsters.php' method='POST'>
        <?php echo "
            <input type='hidden' id='type' name='type' value='monsters'>
            <input type='hidden' id='pokemon_id' name='pokemon_id' value='" . $row['pokemon_id'] . "'>
            <input type='hidden' id='cur_form' name='cur_form' value='" . $row['form'] . "'>
            <input type='hidden' id='cur_distance' name='cur_distance' value='" . $row['distance'] . "'>
            <input type='hidden' id='cur_min_iv' name='cur_min_iv' value='" . $row['min_iv'] . "'>
            <input type='hidden' id='cur_max_iv' name='cur_max_iv' value='" . $row['max_iv'] . "'>
            <input type='hidden' id='cur_min_cp' name='cur_min_cp' value='" . $row['min_cp'] . "'>
            <input type='hidden' id='cur_max_cp' name='cur_max_cp' value='" . $row['max_cp'] . "'>
            <input type='hidden' id='cur_min_level' name='cur_min_level' value='" . $row['min_level'] . "'>
            <input type='hidden' id='cur_max_level' name='cur_max_level' value='" . $row['max_level'] . "'>
            <input type='hidden' id='cur_min_weight' name='cur_min_weight' value='" . $row['min_weight'] . "'>
            <input type='hidden' id='cur_max_weight' name='cur_max_weight' value='" . $row['max_weight'] . "'>
            <input type='hidden' id='cur_gender' name='cur_gender' value='" . $row['gender'] . "'>
            <input type='hidden' id='cur_atk' name='cur_atk' value='" . $row['atk'] . "'>
            <input type='hidden' id='cur_def' name='cur_def' value='" . $row['def'] . "'>
            <input type='hidden' id='cur_sta' name='cur_sta' value='" . $row['sta'] . "'>
            <input type='hidden' id='cur_max_atk' name='cur_max_atk' value='" . $row['max_atk'] . "'>
            <input type='hidden' id='cur_max_def' name='cur_max_def' value='" . $row['max_def'] . "'>
            <input type='hidden' id='cur_max_sta' name='cur_max_sta' value='" . $row['max_sta'] . "'>
            <input type='hidden' id='cur_great_league_ranking' name='cur_great_league_ranking' value='" . $row['great_league_ranking'] . "'>
            <input type='hidden' id='cur_great_league_ranking_min_cp' name='cur_great_league_ranking_min_cp' value='" . $row['great_league_ranking_min_cp'] . "'>
            <input type='hidden' id='cur_ultra_league_ranking' name='cur_ultra_league_ranking' value='" . $row['ultra_league_ranking'] . "'>
            <input type='hidden' id='cur_ultra_league_ranking_min_cp' name='cur_ultra_league_ranking_min_cp' value='" . $row['ultra_league_ranking_min_cp'] . "'>
            " ?>
        <input class="btn btn-danger" type='submit' name='delete'
            value='<?php echo i8ln("DELETE"); ?>'>
    </form>
    <button type="button" class="btn btn-secondary"
        data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
</div>

