<?php

// Replace Default Values if Set

if ( $row['min_iv'] == $monster_defaults['min_iv'] ) { $row['min_iv'] = ""; }
if ( $row['max_iv'] == $monster_defaults['max_iv'] ) { $row['max_iv'] = ""; }
if ( $row['min_cp'] == $monster_defaults['min_cp'] ) { $row['min_cp'] = ""; }
if ( $row['max_cp'] == $monster_defaults['max_cp'] ) { $row['max_cp'] = ""; }
if ( $row['min_level'] == $monster_defaults['min_level'] ) { $row['min_level'] = ""; }
if ( $row['max_level'] == $monster_defaults['max_level'] ) { $row['max_level'] = ""; }
if ( $row['min_weight'] == $monster_defaults['min_weight'] ) { $row['min_weight'] = ""; }
if ( $row['max_weight'] == $monster_defaults['max_weight'] ) { $row['max_weight'] = ""; }

if ( $row['great_league_ranking'] == $monster_defaults['great_league_ranking'] ) { $row['great_league_ranking'] = ""; }
if ( $row['ultra_league_ranking'] == $monster_defaults['ultra_league_ranking'] ) { $row['ultra_league_ranking'] = ""; }
if ( $row['great_league_ranking_min_cp'] == $monster_defaults['great_league_ranking_min_cp'] ) { $row['great_league_ranking_min_cp'] = ""; }
if ( $row['ultra_league_ranking_min_cp'] == $monster_defaults['ultra_league_ranking_min_cp'] ) { $row['ultra_league_ranking_min_cp'] = ""; }


$form_name = get_form_name($row['pokemon_id'], $row['form']);

echo "
    <form action='./actions/monsters.php' method='POST'>
    <input type='hidden' id='gen' name='gen' value='".$_GET['gen']."'>
    ";

if ($row['pokemon_id'] == '0') {
	echo "<center><font size=8><strong>".i8ln("ALL")."</strong></font></center>";
} else {
        $pokemon_name=get_mons($row['pokemon_id']);
	echo "<td><center>$PkmnImg_100</center></td>";
        echo "<center><font size=5>".$pokemon_name."</font></center>";
}

?>
<div class="modal-body">

    <?php

        echo "
        <input type='hidden' id='type' name='type' value='monsters'>
        <input type='hidden' id='uid' name='uid' value='" . $row['uid'] . "'>
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
		aria-controls="pills-other-<?php echo $pkm_unique_id ?>" aria-selected="false"><?php echo i8ln("Other"); ?></a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tab-<?php echo $pkm_unique_id ?>Content">
        <div class="tab-pane fade show active" id="pills-stats-<?php echo $pkm_unique_id ?>" role="tabpanel"
	    aria-labelledby="pills-stats-tab-<?php echo $pkm_unique_id ?>">
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
			    <input type="radio" name="use_areas_pkmn" id="use_areas_<?php echo $pkm_unique_id; ?>" value="areas" <?php echo $area_check; ?> 
                            onclick="areas('<?php echo $pkm_unique_id; ?>')">
                            <?php echo i8ln("Use Areas"); ?>
                        </label>
                        <label class="btn btn-secondary mr-2">
			    <input type="radio" name="use_areas_pkmn" id="use_areas_<?php echo $pkm_unique_id; ?>" value="distance" <?php echo $distance_check; ?> 
                            onclick="areas('<?php echo $pkm_unique_id; ?>')">
                            <?php echo i8ln("Set Distance"); ?>
                        </label>
                        </div>
                    </div>
		    <div class="input-group mt-2">
			<input type="number" id='distance_<?php echo $pkm_unique_id; ?>' name='distance' value='<?php echo $row['distance'] ?>' <?php echo $style; ?>
                            min='0' max='<?php echo $_SESSION['maxDistance']; ?>' class="form-control text-center">
                        <div class="input-group-append" id="distance_label_<?php echo $pkm_unique_id; ?>" <?php echo $style; ?>>
			    <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
                        </div>
                    </div>
                </div>
	    </div>
            <?php } else { ?>
                <input type="hidden" id='distance' name='distance' value='<?php echo $row['distance'] ?>' min='0'>
            <?php } ?>

            <div class="form-row align-items-center">
		<div class="col-sm-12 my-1">
                    <div class="input-group mb-1">
		    <input type="checkbox" name="noiv" id="noiv_<?php echo $pkm_unique_id; ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" 
		    <?php if ( $row['min_iv'] == "-1" ) { echo "checked"; $row['min_iv'] = ""; $disabled="disabled"; } else { $disabled=""; } ?> 
                    onChange="setnoiv('<?php echo $pkm_unique_id; ?>')"
                    >
                       &nbsp;&nbsp;<?php echo i8ln("Include Pokemon with unknown IV"); ?>
                    </div>
		    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo i8ln("IV"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                        </div>
			<input type='number' id='min_iv_<?php echo $pkm_unique_id; ?>' name='min_iv' size=1 
                            value='<?php echo $row['min_iv'] ?>'
                            placeholder='<?php echo $monster_defaults['min_iv']; ?>'
			    min='<?php echo $monster_defaults['min_iv'] ?>' 
			    max='<?php echo $monster_defaults['max_iv'] ?>' 
			    class="form-control text-center" <?php echo $disabled; ?> >
                        <div class="input-group-append">
			    <div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                        </div>
			<input type='number' id='max_iv' name='max_iv' size=1 
                            value='<?php echo $row['max_iv'] ?>' 
                            placeholder='<?php echo $monster_defaults['max_iv']; ?>'
			    min='<?php echo $monster_defaults['min_iv'] ?>' 
			    max='<?php echo $monster_defaults['max_iv'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text">&nbsp;&nbsp;&nbsp;<?php echo i8ln("CP"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                        </div>
			<input type='number' id='min_cp' name='min_cp' size=1 
                            value='<?php echo $row['min_cp'] ?>'
                            placeholder='<?php echo $monster_defaults['min_cp']; ?>'
			    min='<?php echo $monster_defaults['min_cp'] ?>' 
			    max='<?php echo $monster_defaults['max_cp'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                        </div>
			<input type='number' id='max_cp' name='max_cp' size=1 
                            value='<?php echo $row['max_cp'] ?>'
                            placeholder='MAX'
			    min='<?php echo $monster_defaults['min_cp'] ?>' 
			    max='<?php echo $monster_defaults['max_cp'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text">&nbsp;&nbsp;&nbsp;<?php echo i8ln("LVL"); ?>&nbsp;&nbsp;&nbsp;</div>
                        </div>
			<input type='number' id='min_level' name='min_level' size=1 
                            value='<?php echo $row['min_level'] ?>'
                            placeholder='<?php echo $monster_defaults['min_level']; ?>'
			    min='<?php echo $monster_defaults['min_level'] ?>' 
			    max='<?php echo $monster_defaults['max_level'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                        </div>
                        <input type='number' id='max_level' name='max_level' size=1
                            placeholder='<?php echo $monster_defaults['max_level']; ?>'
			    value='<?php echo $row['max_level'] ?>' 
			    min='<?php echo $monster_defaults['min_level'] ?>' 
			    max='<?php echo $monster_defaults['max_level'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text"><?php echo i8ln("Weight"); ?></div>
                        </div>
                        <input type='number' id='min_weight' name='min_weight' size=2
                            placeholder='<?php echo $monster_defaults['min_weight']; ?>'
			    value='<?php echo $row['min_weight'] ?>' 
			    min='<?php echo $monster_defaults['min_weight'] ?>' 
                            max='<?php echo $monster_defaults['max_weight'] ?>'
                            class="form-control text-center">
                        <div class="input-group-append">
			    <div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                        </div>
                        <input type='number' id='max_weight' name='max_weight' size=4
                            placeholder='MAX'
			    value='<?php echo $row['max_weight'] ?>' 
			    min='<?php echo $monster_defaults['min_weight'] ?>' 
                            max='<?php echo $monster_defaults['max_weight'] ?>'
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
		    <label><?php echo i8ln("MIN STATS"); ?></label>
                    <div class="input-group">
			<input type='number' id='atk' name='atk' size=1 
                            value='<?php echo $row['atk'] ?>' 
                            min='<?php echo $monster_defaults['atk'] ?>'
			    max='<?php echo $monster_defaults['max_atk'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <div class="input-group-text"><?php echo i8ln("ATK"); ?></div>
                        </div>
			<input type='number' id='def' name='def' size=1 
                            value='<?php echo $row['def'] ?>' 
                            min='<?php echo $monster_defaults['def'] ?>'
			    max='<?php echo $monster_defaults['max_def'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("DEF"); ?></span>
                        </div>
			<input type='number' id='sta' name='sta' size=1 
                            value='<?php echo $row['sta'] ?>' 
                            min='<?php echo $monster_defaults['sta'] ?>'
			    max='<?php echo $monster_defaults['max_sta'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("STA"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
		    <label><?php echo i8ln("MAX STATS"); ?></label>
                    <div class="input-group">
			<input type='number' id='max_atk' name='max_atk' size=1 
                            value='<?php echo $row['max_atk'] ?>'
			    min='<?php echo $monster_defaults['atk'] ?>' 
			    max='<?php echo $monster_defaults['max_atk'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <div class="input-group-text"><?php echo i8ln("ATK"); ?></div>
                        </div>
			<input type='number' id='max_def' name='max_def' size=1 
                            value='<?php echo $row['max_def'] ?>'
			    min='<?php echo $monster_defaults['def'] ?>' 
			    max='<?php echo $monster_defaults['max_def'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("DEF"); ?></span>
                        </div>
			<input type='number' id='max_sta' name='max_sta' size=1 
                            value='<?php echo $row['max_sta'] ?>'
			    min='<?php echo $monster_defaults['sta'] ?>' 
			    max='<?php echo $monster_defaults['max_sta'] ?>' 
                            class="form-control text-center">
                        <div class="input-group-append">
			    <span class="input-group-text"><?php echo i8ln("STA"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
	</div>

        <div class="tab-pane fade" id="pills-pvp-<?php echo $pkm_unique_id ?>" role="tabpanel"
	    aria-labelledby="pills-pvp-tab-<?php echo $pkm_unique_id ?>">
            <b><font style="color:darkred;"><?php echo i8ln("Only fill this section if you want to track PvP"); ?>.</font></b><hr>
	    </center>

            <?php

            $great_checked = ""; $great_display = "none";
            $ultra_checked = ""; $ultra_display = "none";
	    $none_checked = "";

            if ( $row['great_league_ranking'] > 0 ) 
            { 
		    $great_display = "block"; 
		    $great_checked = "checked"; 
	    } else if ( $row['ultra_league_ranking'] > 0 ) 
            { 
		    $ultra_display = "block"; 
		    $ultra_checked = "checked"; 
	    } else {
		    $none_checked = "checked"; 
	    }


            ?>

            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><?php echo i8ln("Track PvP League"); ?>&nbsp;</div>
                            </div>
                        </div>
                        <label class="btn btn-secondary">
			    <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>" 
                                   value="none" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $none_checked; ?>><?php echo i8ln("None"); ?>
                        </label>
                        <label class="btn btn-secondary">
			    <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>" 
                                   value="great" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $great_checked; ?>><?php echo i8ln("Great"); ?>
                        </label>
                        <label class="btn btn-secondary">
			    <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>" 
                                   value="ultra" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $ultra_checked; ?>><?php echo i8ln("Ultra"); ?>
                        </label>
                    </div>
                </div>
    	    </div>

            <div class="form-row align-items-center" id="league_great_<?php echo $pkm_unique_id; ?>" style="display:<?php echo $great_display; ?>;">
		<div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text"><?php echo i8ln("MIN Rank"); ?></div>
                        </div>
			<input type='number' id='great_league_ranking_<?php echo $pkm_unique_id; ?>' name='great_league_ranking' size=1
                            value='<?php echo $row['great_league_ranking'] ?>' min='1' max='<?php echo $_SESSION['pvpFilterMaxRank']; ?>' 
                            class="form-control text-center">
                        <div class="input-group-prepend">
			    <span class="input-group-text"><?php echo i8ln("MIN CP"); ?></span>
                        </div>
                        <input type='number' id='great_league_ranking_min_cp_<?php echo $pkm_unique_id; ?>' name='great_league_ranking_min_cp' size=1 
                            value='<?php echo $row['great_league_ranking_min_cp'] ?>' min='<?php echo $_SESSION['pvpFilterGreatMinCP']; ?>' max='4096'
                            class="form-control text-center">
		    </div>
                    <b><font style="color:darkred;"><?php echo i8ln("Ranking should be between 1 and")." ".$_SESSION['pvpFilterMaxRank']; ?></font></b>
                </div>
	    </div>

            <div class="form-row align-items-center" id="league_ultra_<?php echo $pkm_unique_id; ?>" style="display:<?php echo $ultra_display; ?>;">
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text"><?php echo i8ln("MIN Rank"); ?></div>
                        </div>
                        <input type='number' id='ultra_league_ranking_<?php echo $pkm_unique_id; ?>' name='ultra_league_ranking' size=1 
                            value='<?php echo $row['ultra_league_ranking'] ?>' min=1 max='<?php echo $_SESSION['pvpFilterMaxRank']; ?>'
                            class="form-control text-center">
                        <div class="input-group-prepend">
			    <span class="input-group-text"><?php echo i8ln("MIN CP"); ?></span>
                        </div>
                        <input type='number' id='ultra_league_ranking_min_cp_<?php echo $pkm_unique_id; ?>' name='ultra_league_ranking_min_cp' size=1 
                            value='<?php echo $row['ultra_league_ranking_min_cp'] ?>' min='<?php echo $_SESSION['pvpFilterUltraMinCP']; ?>' max='4096'
                            class="form-control text-center">
		    </div>
                    <b><font style="color:darkred;"><?php echo i8ln("Ranking should be between 1 and")." ".$_SESSION['pvpFilterMaxRank']; ?></font></b>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-other-<?php echo $pkm_unique_id ?>" role="tabpanel"
            aria-labelledby="pills-other-tab-<?php echo $pkm_unique_id ?>">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <div class="input-group">
                    <div class="input-group-prepend">
			<div class="input-group-text"><?php echo i8ln("Form"); ?></div>
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
                        <?php echo $checked; ?>> <?php echo i8ln($value); ?>
                </label>
                <?php
                                }
                                ?>
            </div>
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

                   if ($row['clean'] == 0) { $checked0 = 'checked'; } else { $checked0 = ''; }
	  	   if ($row['clean'] == 1) { $checked1 = 'checked'; } else { $checked1 = ''; }


                ?>

                <label class="btn btn-secondary">
		    <input type="radio" name="clean" id="clean_0" value="clean_0" <?php echo $checked0; ?>> <?php echo i8ln("No"); ?>
                </label>
                <label class="btn btn-secondary">
		    <input type="radio" name="clean" id="clean_1" value="clean_1" <?php echo $checked1; ?>> <?php echo i8ln("Yes"); ?>
                </label>
            </div>
            <hr>
            <?php if (isset($allowed_templates["mons"])) {
                echo '<div class="form-row align-items-center">
                    <div class="col-sm-12 my-1">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                    <div class="input-group-text">Template</div>
                                </div>
                            </div>';
                            foreach ( $allowed_templates["mons"] as $key => $name ) {
                                echo '<label class="btn btn-secondary">';
		                echo '<input type="radio" name="template" id="' . $key . '" value="' . $key . '" ' . (($key == $row['template']) ? 'checked' : '') . '>';
                                echo $name . '</label>';
                            }
                        echo '</div>
                    </div>
                </div>';
            } ?>
        </div>
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
