<?php

$form_name = get_form_name($row['pokemon_id'], $row['form']);

echo "
    <form action='./form_action.php' method='POST'>
    ";

if ($row['pokemon_id'] == '0') {
        echo "<center><font size=8><strong>ALL</strong></font></center>";
} else {
        echo "<td><center>$PkmnImg_100</center></td>";
}

?>
<div class="modal-body">

    <?php

        echo "
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
    ";
        ?>

    <ul class="nav nav-pills mb-3 mx-auto justify-content-center" id="pills-tab-<?php echo $pkm_unique_id ?>"
        role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-stats-tab-<?php echo $pkm_unique_id ?>" data-toggle="pill"
                href="#pills-stats-<?php echo $pkm_unique_id ?>" role="tab"
                aria-controls="pills-stats-<?php echo $pkm_unique_id ?>" aria-selected="true">Stats</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-pvp-tab-<?php echo $pkm_unique_id ?>" data-toggle="pill"
                href="#pills-pvp-<?php echo $pkm_unique_id ?>" role="tab"
                aria-controls="pills-pvp-<?php echo $pkm_unique_id ?>" aria-selected="false">PvP</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-other-tab-<?php echo $pkm_unique_id ?>" data-toggle="pill"
                href="#pills-other-<?php echo $pkm_unique_id ?>" role="tab"
                aria-controls="pills-other-<?php echo $pkm_unique_id ?>" aria-selected="false">Other</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tab-<?php echo $pkm_unique_id ?>Content">
        <div class="tab-pane fade show active" id="pills-stats-<?php echo $pkm_unique_id ?>" role="tabpanel"
            aria-labelledby="pills-stats-tab-<?php echo $pkm_unique_id ?>">
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Distance</div>
                        </div>
                        <input type="number" id='distance' name='distance' value='<?php echo $row['distance'] ?>'
                            min='0' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">m</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&nbsp;&nbsp;&nbsp;&nbsp;IV&nbsp;&nbsp;&nbsp;&nbsp;</div>
                        </div>
                        <input type='number' id='min_iv' name='min_iv' size=1 value='<?php echo $row['min_iv'] ?>'
                            min='-1' max='100' class="form-control text-center">
                        <div class="input-group-append">
                            <div class="input-group-text">MIN</div>
                        </div>
                        <input type='number' id='max_iv' name='max_iv' size=1 value='<?php echo $row['max_iv'] ?>'
                            min='-1' max='100' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">MAX</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&nbsp;&nbsp;&nbsp;CP&nbsp;&nbsp;&nbsp;&nbsp;</div>
                        </div>
                        <input type='number' id='min_cp' name='min_cp' size=1 value='<?php echo $row['min_cp'] ?>'
                            min='0' max='9000' class="form-control text-center">
                        <div class="input-group-append">
                            <div class="input-group-text">MIN</div>
                        </div>
                        <input type='number' id='max_cp' name='max_cp' size=1 value='<?php echo $row['max_cp'] ?>'
                            min='0' max='9000' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">MAX</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">&nbsp;&nbsp;&nbsp;LVL&nbsp;&nbsp;&nbsp;</div>
                        </div>
                        <input type='number' id='min_level' name='min_level' size=1 value='<?php echo $row['min_cp'] ?>'
                            min='0' max='50' class="form-control text-center">
                        <div class="input-group-append">
                            <div class="input-group-text">MIN</div>
                        </div>
                        <input type='number' id='max_level' name='max_level' size=1
                            value='<?php echo $row['max_level'] ?>' min='0' max='50' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">MAX</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Weight</div>
                        </div>
                        <input type='number' id='min_weight' name='min_weight' size=2
                            value='<?php echo $row['min_weight'] ?>' min='0' max='9000000'
                            class="form-control text-center">
                        <div class="input-group-append">
                            <div class="input-group-text">MIN</div>
                        </div>
                        <input type='number' id='max_weight' name='max_weight' size=4
                            value='<?php echo $row['max_weight'] ?>' min='0' max='9000000'
                            class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">MAX</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <label>MIN STATS</label>
                    <div class="input-group">
                        <input type='number' id='atk' name='atk' size=1 value='<?php echo $row['atk'] ?>' min='0'
                            max='15' class="form-control text-center">
                        <div class="input-group-append">
                            <div class="input-group-text">ATK</div>
                        </div>
                        <input type='number' id='def' name='def' size=1 value='<?php echo $row['def'] ?>' min='0'
                            max='15' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">DEF</span>
                        </div>
                        <input type='number' id='sta' name='sta' size=1 value='<?php echo $row['sta'] ?>' min='0'
                            max='15' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">STA</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <label>MAX STATS</label>
                    <div class="input-group">
                        <input type='number' id='max_atk' name='max_atk' size=1 value='<?php echo $row['max_atk'] ?>'
                            min='0' max='15' class="form-control text-center">
                        <div class="input-group-append">
                            <div class="input-group-text">ATK</div>
                        </div>
                        <input type='number' id='max_def' name='max_def' size=1 value='<?php echo $row['max_def'] ?>'
                            min='0' max='15' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">DEF</span>
                        </div>
                        <input type='number' id='max_sta' name='max_sta' size=1 value='<?php echo $row['max_sta'] ?>'
                            min='0' max='15' class="form-control text-center">
                        <div class="input-group-append">
                            <span class="input-group-text">STA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-pvp-<?php echo $pkm_unique_id ?>" role="tabpanel"
            aria-labelledby="pills-pvp-tab-<?php echo $pkm_unique_id ?>">
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <label>PvP Great</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">MIN Rank</div>
                        </div>
                        <input type='number' id='great_league_ranking' name='great_league_ranking' size=1
                            value='<?php echo $row['great_league_ranking'] ?>' min='0' max='4096'
                            class="form-control text-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">MIN CP</span>
                        </div>
                        <input type='number' id='great_league_ranking_min_cp' name='great_league_ranking_min_cp' size=1
                            value='<?php echo $row['great_league_ranking_min_cp'] ?>' min='0' max='4096'
                            class="form-control text-center">
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <label>PvP Ultra</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">MIN Rank</div>
                        </div>
                        <input type='number' id='ultra_league_ranking' name='ultra_league_ranking' size=1
                            value='<?php echo $row['ultra_league_ranking'] ?>' min='0' max='4096'
                            class="form-control text-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">MIN CP</span>
                        </div>
                        <input type='number' id='ultra_league_ranking_min_cp' name='ultra_league_ranking_min_cp' size=1
                            value='<?php echo $row['ultra_league_ranking_min_cp'] ?>' min='0' max='4096'
                            class="form-control text-center">
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-other-<?php echo $pkm_unique_id ?>" role="tabpanel"
            aria-labelledby="pills-other-tab-<?php echo $pkm_unique_id ?>">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Form</div>
                    </div>
                </div>
                <?php
                                $forms = get_all_forms($row['pokemon_id']);
                                ksort($forms);
                                foreach ($forms as $key => $value) {
                                        if ($key == $row['form']) {
                                                $checked = 'checked';
                                        } else {
                                                $checked = '';
                                        }
                                ?>
                <label class="btn btn-secondary">
                    <input type="radio" name="form" id="form_<?php echo $key; ?>" value="form_<?php echo $key; ?>"
                        <?php echo $checked; ?>> <?php echo $value; ?>
                </label>
                <?php
                                }
                                ?>
            </div>
            <hr>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Gender</div>
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
                    <input type="radio" name="gender" id="gender_0" value="gender_0" <?php echo $checked0; ?>> All
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="gender" id="gender_1" value="gender_1" <?php echo $checked1; ?>> Male
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="gender" id="gender_2" value="gender_2" <?php echo $checked2; ?>> Female
                </label>
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