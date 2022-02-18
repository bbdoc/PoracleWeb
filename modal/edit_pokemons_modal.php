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

if ( $row['pvp_ranking_best'] == $monster_defaults['pvp_ranking_best'] ) { $row['pvp_ranking_best'] = ""; }
if ( $row['pvp_ranking_worst'] == $monster_defaults['pvp_ranking_worst'] ) { $row['pvp_ranking_worst'] = ""; }
if ( $row['pvp_ranking_min_cp'] == $monster_defaults['pvp_ranking_min_cp'] ) { $row['pvp_ranking_min_cp'] = ""; }


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

    <input type='hidden' id='type' name='type' value='monsters'>
    <input type='hidden' id='uid' name='uid' value='<?php echo $row['uid']; ?>'>

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
	    <?php include "./include/edit_area_distance.php"; ?>

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

                    <div class='alert alert-info fade show' role='alert' style='padding:3px; margin:3px;'>
                       <?php echo i8ln("Only fill this section if you want to track PvP"); ?><br>
                       <?php echo i8ln("Ranking should be between 1 and")." ".$_SESSION['pvpFilterMaxRank']; ?>
                    </div>

	    </center>

            <?php

            $great_checked = ""; $great_display = "none";
            $ultra_checked = ""; $ultra_display = "none";
	    $none_checked = "";

	    $little_checked = "";
	    $great_checked = "";
	    $ultra_checked = "";

            if ( $row['pvp_ranking_league'] == 500 ) 
            { 
		    $pvp_display = "block"; 
		    $little_checked = "checked"; 
	    } else if ( $row['pvp_ranking_league'] == 1500 ) 
	    { 
                    $pvp_display = "block";
		    $great_checked = "checked";
            } else if ( $row['pvp_ranking_league'] == 2500 )
            {
                    $pvp_display = "block";
                    $ultra_checked = "checked";

	    } else {
		    $pvp_display = "none"; 
		    $none_checked = "checked"; 
	    }


            ?>

            <div class="form-row align-items-center">
                <div class="col-sm-12 my-1">
                    <div class="btn-group btn-group-toggle mt-1" data-toggle="buttons">
                        <div class="input-group">
                            <div class="input-group">
                                <div class="input-group-text"><?php echo i8ln("Track PvP League"); ?>&nbsp;</div>
                            </div>
			</div>
                     </div>
                     <div class="btn-group btn-group-toggle mt-1" data-toggle="buttons">
                        <label class="btn btn-secondary">
			    <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>" 
                                   value="0" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $none_checked; ?>><?php echo i8ln("None"); ?>
			</label>
                        <?php if ( $_SESSION['pvpLittleLeagueAllowed'] == "True" ) { ?>
                        <label class="btn btn-secondary">
                            <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>"
                                   value="500" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $little_checked; ?>><?php echo i8ln("Little"); ?>
			</label>
                        <?php } ?>
                        <label class="btn btn-secondary">
			    <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>" 
                                   value="1500" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $great_checked; ?>><?php echo i8ln("Great"); ?>
                        </label>
                        <label class="btn btn-secondary">
			    <input type="radio" name="league" id="league_<?php echo $pkm_unique_id; ?>" 
                                   value="2500" onclick="setpvp('<?php echo $pkm_unique_id; ?>')" <?php echo $ultra_checked; ?>><?php echo i8ln("Ultra"); ?>
                        </label>
                    </div>
                </div>
    	    </div>


	    <input type="hidden" id="pvpFilterLittleMinCP" name="pvpFilterLittleMinCP" value="<?php echo $_SESSION['pvpFilterLittleMinCP']; ?>">
	    <input type="hidden" id="pvpFilterGreatMinCP" name="pvpFilterGreatMinCP" value="<?php echo $_SESSION['pvpFilterGreatMinCP']; ?>">
	    <input type="hidden" id="pvpFilterUltraMinCP" name="pvpFilterultraMinCP" value="<?php echo $_SESSION['pvpFilterUltraMinCP']; ?>">

            <div class="form-row align-items-center" id="pvp_league_<?php echo $pkm_unique_id; ?>" style="display:<?php echo $pvp_display; ?>;">
		<div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
			    <div class="input-group-text"><?php echo i8ln("Rank between"); ?></div>
			</div>
			<?php if ($row['pvp_ranking_best'] == 0) { $row['pvp_ranking_best'] = ""; } ?>
			<input type='number' id='pvp_ranking_best_<?php echo $pkm_unique_id; ?>' name='pvp_ranking_best' size=1
                            value='<?php echo $row['pvp_ranking_best'] ?>' min='1' max='<?php echo $_SESSION['pvpFilterMaxRank']; ?>' 
                            class="form-control text-center">
                        <div class="input-group-prepend">
			    <span class="input-group-text">&nbsp;&nbsp;&nbsp;<?php echo i8ln("and"); ?></span>
			</div>
                        <input type='number' id='pvp_ranking_worst_<?php echo $pkm_unique_id; ?>' name='pvp_ranking_worst' size=1
                            value='<?php echo $row['pvp_ranking_worst'] ?>' min='1' max='<?php echo $_SESSION['pvpFilterMaxRank']; ?>'
                            class="form-control text-center">
		    </div>
		</div>
                <div class="col-sm-12 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><?php echo i8ln("MIN CP"); ?></span>
                        </div>
                        <input type='number' id='pvp_ranking_min_cp_<?php echo $pkm_unique_id; ?>' name='pvp_ranking_min_cp' size=1
                            value='<?php echo $row['pvp_ranking_min_cp'] ?>' min='0' max='4096'
                            class="form-control text-center">
                    </div>
                </div>

                <?php if ( count($_SESSION['pvpCaps']) > 1 ) { ?>
                <div class="col-sm-12 my-1">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><?php echo i8ln("Cap"); ?></div>
                            </div>
                        </div>
                        <?php
                            $pvpCaps = $_SESSION['pvpCaps'];
                            array_push($pvpCaps, $_SESSION['defaultPvpCap']);
                            $pvpCaps = array_unique($pvpCaps);
                            sort($pvpCaps);
                            foreach($pvpCaps as $key => $cap)
                            {
                                    if ( $cap == $row['pvp_ranking_cap'] ) { $checked = "checked"; } else { $checked = ""; } 
                                    if ( $cap == 0 ) { $display_cap = "ALL"; } else { $display_cap = $cap; } 
                        ?>
                        <label class="btn btn-secondary">
                            <input type="radio" name="cap" id="cap_<?php echo $cap; ?>" value="cap_<?php echo $cap; ?>" <?php echo $checked; ?>><?php echo i8ln($display_cap); ?>
                        </label>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

	    </div>

        </div>
        <div class="tab-pane fade" id="pills-other-<?php echo $pkm_unique_id ?>" role="tabpanel"
            aria-labelledby="pills-other-tab-<?php echo $pkm_unique_id ?>">
            <div class="btn-group-justified btn-group-toggle" data-toggle="buttons">
                <div class="input-group">
                    <div class="input-group mb-1">
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
                <label class="btn btn-secondary mb-1">
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
            <?php if (strpos($_SESSION['type'], ':user') === false) {  ?>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><?php echo i8ln("Role to ping"); ?></div>
                    </div>
                    <input type='text' id='content_edit' name='content' size=50 class="form-control" maxlength="255" value="<?php echo $row['ping'] ?>">
                </div>
            </div>
            <hr>
            <?php } else { ?>
            <input type="hidden" id='content_edit' name='content' value=''>
            <?php } ?>
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
            <?php if ( $enable_templates == "True" && count($templates_list) > 1 ) {
                echo '<div class="form-row align-items-center">
                    <div class="col-sm-12 my-1">
                        <div class="btn-group-justify btn-group-toggle" data-toggle="buttons">
                        <div class="input-group">
                            <div class="input-group mb-1">
                                    <div class="input-group-text">Template</div>
                                </div>
                            </div>';
                            foreach ( $templates_list as $key => $name ) {
                                echo '<label class="btn btn-secondary mb-1 mr-1">';
		                echo '<input type="radio" name="template" id="' . $name . '" value="' . $name . '" ' . (($name == $row['template']) ? 'checked' : '') . '>';
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
