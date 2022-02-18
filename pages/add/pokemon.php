<?php

if ( $disable_mons == "True" ) {
        header("Location: $redirect_url");
        exit();
}

?>

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW MONSTER ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php
                        $clean_0_checked = "";
                        $clean_1_checked = "";
                        if ($all_mon_cleaned == "1") {
                            $clean_1_checked = 'checked';
                        } else {
                            $clean_0_checked = 'checked';
                        }

                    ?>

                    <form action='./actions/monsters.php' method='POST'>

                        <?php include "./include/add_area_distance.php"; ?>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
				<div class="input-group mb-1">
				<input type="checkbox" name="noiv" id="noiv_add" data-toggle="toggle" onChange="setnoiv('add')"
                                       data-onstyle="success" data-offstyle="danger" data-size="sm">
				    &nbsp;&nbsp;<?php echo i8ln("Include Pokemon with unknown IV"); ?>
                                </div>
				<div class="input-group">

                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
					    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo i8ln("IV"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
				    <input type='number' id='min_iv_add' name='min_iv' size=1 
					placeholder='<?php echo $monster_defaults['min_iv']; ?>' 
                                        min='<?php echo $monster_defaults['min_iv']; ?>' 
                                        max='<?php echo $monster_defaults['max_iv']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
				    <input type='number' id='max_iv' name='max_iv' size=1 
					placeholder='<?php echo $monster_defaults['max_iv']; ?>' 
					min='<?php echo $monster_defaults['min_iv']; ?>' 
                                        max='<?php echo $monster_defaults['max_iv']; ?>'
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
                                        <div class="input-group-text">
					    &nbsp;&nbsp;&nbsp;<?php echo i8ln("CP"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
				    <input type='number' id='min_cp' name='min_cp' size=1 
                                        placeholder='<?php echo $monster_defaults['min_cp']; ?>' 
                                        min='<?php echo $monster_defaults['min_cp']; ?>' 
                                        max='<?php echo $monster_defaults['max_cp']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
				    <input type='number' id='max_cp' name='max_cp' size=1 
                                        placeholder='MAX' 
                                        min='<?php echo $monster_defaults['min_cp']; ?>'
					max='<?php echo $monster_defaults['max_cp']; ?>' 
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
					<div class="input-group-text">&nbsp;&nbsp;&nbsp;<?php echo i8ln("LVL"); ?>&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
				    <input type='number' id='min_level' name='min_level' size=1 
                                         placeholder='<?php echo $monster_defaults['min_level']; ?>'
                                         min='<?php echo $monster_defaults['min_level']; ?>'
				 	 max='<?php echo $monster_defaults['max_level']; ?>'
                                         class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
				    <input type='number' id='max_level' name='max_level' size=1 
                                        placeholder='<?php echo $monster_defaults['max_level']; ?>' 
                                        min='<?php echo $monster_defaults['min_level']; ?>'
					max='<?php echo $monster_defaults['max_level']; ?>' 
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
                                        min='<?php echo $monster_defaults['min_weight']; ?>'
					max='<?php echo $monster_defaults['max_weight']; ?>' 
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
				    <input type='number' id='max_weight' name='max_weight' size=4
                                        placeholder='MAX'
					min='<?php echo $monster_defaults['min_weight']; ?>' 
                                        max='<?php echo $monster_defaults['max_weight']; ?>' 
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
				<label><?php echo i8ln("MIN STATS"); ?></label>
                                <div class="input-group">
				    <input type='number' id='atk' name='atk' size=1 
					placeholder='<?php echo $monster_defaults['atk']; ?>' 
					min='<?php echo $monster_defaults['atk']; ?>' 
                                        max='<?php echo $monster_defaults['max_atk']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("ATK"); ?></div>
                                    </div>
				    <input type='number' id='def' name='def' size=1 
					placeholder='<?php echo $monster_defaults['def']; ?>' 
					min='<?php echo $monster_defaults['def']; ?>' 
                                        max='<?php echo $monster_defaults['max_def']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("DEF"); ?></span>
                                    </div>
				    <input type='number' id='sta' name='sta' size=1 
					placeholder='<?php echo $monster_defaults['sta']; ?>' 
					min='<?php echo $monster_defaults['sta']; ?>' 
                                        max='<?php echo $monster_defaults['max_sta']; ?>'
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
					placeholder='<?php echo $monster_defaults['max_atk']; ?>' 
					min='<?php echo $monster_defaults['atk']; ?>' 
                                        max='<?php echo $monster_defaults['max_atk']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("ATK"); ?></div>
                                    </div>
				    <input type='number' id='max_def' name='max_def' size=1 
					placeholder='<?php echo $monster_defaults['max_def']; ?>' 
					min='<?php echo $monster_defaults['def']; ?>' 
                                        max='<?php echo $monster_defaults['max_def']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("DEF"); ?></span>
                                    </div>
				    <input type='number' id='max_sta' name='max_sta' size=1
					placeholder='<?php echo $monster_defaults['max_sta']; ?>' 
					min='<?php echo $monster_defaults['sta']; ?>' 
                                        max='<?php echo $monster_defaults['max_sta']; ?>'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("STA"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

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
                                        <input type="radio" name="league" id="league_add" value="0" onclick="setpvp('add')" checked><?php echo i8ln("None"); ?>
				    </label>
                                    <?php if ( $_SESSION['pvpLittleLeagueAllowed'] == "True" ) { ?>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="league" id="league_add" value="500" onclick="setpvp('add')"><?php echo i8ln("Little"); ?>
				    </label>
                                    <?php } ?>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="league" id="league_add" value="1500" onclick="setpvp('add')"><?php echo i8ln("Great"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="league" id="league_add" value="2500" onclick="setpvp('add')"><?php echo i8ln("Ultra"); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="pvpFilterLittleMinCP" name="pvpFilterLittleMinCP" value="<?php echo $_SESSION['pvpFilterLittleMinCP']; ?>">
                        <input type="hidden" id="pvpFilterGreatMinCP" name="pvpFilterGreatMinCP" value="<?php echo $_SESSION['pvpFilterGreatMinCP']; ?>">
	                <input type="hidden" id="pvpFilterUltraMinCP" name="pvpFilterultraMinCP" value="<?php echo $_SESSION['pvpFilterUltraMinCP']; ?>">

			<div class="form-row align-items-center" id="pvp_league_add" style="display:none;">

                            <div class='alert alert-info fade show' role='alert' style='padding:3px; margin:3px;'>
                                   <?php echo i8ln("Only fill this section if you want to track PvP"); ?><br>
                                   <?php echo i8ln("Ranking should be between 1 and")." ".$_SESSION['pvpFilterMaxRank']; ?>
                            </div>

			    <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text"><?php echo i8ln("Rank between"); ?></div>
                                    </div>
                                    <input type='number' id='pvp_ranking_best_add' name='pvp_ranking_best' size=1
				        value='' max='<?php echo $_SESSION['pvpFilterMaxRank']; ?>' class="form-control text-center">
                                    <div class="input-group-prepend">
					<span class="input-group-text">&nbsp;&nbsp;&nbsp;<?php echo i8ln("and"); ?></span>
				    </div>
                                    <input type='number' id='pvp_ranking_worst_add' name='pvp_ranking_worst' size=1
				        value='' max='<?php echo $_SESSION['pvpFilterMaxRank']; ?>' class="form-control text-center">
				</div>
			    </div>

                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo i8ln("MIN CP"); ?></span>
                                    </div>
                                    <input type='number' id='pvp_ranking_min_cp_add'
                                        name='pvp_ranking_min_cp' size=1 value=''
                                        min='0' max='4096' class="form-control text-center">
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
						if ( $cap == $_SESSION['defaultPvpCap'] ) { $checked = "checked"; } else { $checked = ""; }
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

                        <hr>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
					    <div class="input-group-text"><?php echo i8ln("Gender"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
					<input type="radio" name="gender" id="gender_0" value="gender_0" checked><?php echo i8ln("All"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
					<input type="radio" name="gender" id="gender_1" value="gender_1"><?php echo i8ln("Male"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
					<input type="radio" name="gender" id="gender_2" value="gender_2"><?php echo i8ln("Female"); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
						<?php if (strpos($_SESSION['type'], ':user') === false) {  ?>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><?php echo i8ln("Role to ping"); ?></div>
                                        </div>
                                        <input type='text' id='content_add' name='content' maxlength=255 size=50 class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php } else { ?>
							<input type="hidden" id='content_add' name='content' value=''>
						<?php } ?>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
					    <div class="input-group-text"><?php echo i8ln("Clean"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_0" value="clean_0"
                                            <?php echo $clean_0_checked; ?>>
					<?php echo i8ln("No"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_1" value="clean_1"
                                            <?php echo $clean_1_checked; ?>>
					<?php echo i8ln("Yes"); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

			<?php 

			$type = explode(":", $_SESSION['type'], 2); 
			if ( $type[0] == "webhook" ) { $type[0] = "discord"; }
			$templates_locale = @$_SESSION['templates'][$type[0]]['monster'][$_SESSION['locale']];
			$templates_undefined = @$_SESSION['templates'][$type[0]]['monster']['%'];
			$templates_list = array_merge((array)$templates_locale,(array)$templates_undefined);

                        if (count($templates_list) > 1 && $enable_templates == "True" ) {
                            echo '<div class="form-row align-items-center">
                                <div class="col-sm-12 my-1">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <div class="input-group-justify">
                                            <div class="input-group mb-1">
                                                <div class="input-group-text">Template</div>
                                            </div>
                                        </div>';
                                        foreach ( $templates_list as $key => $name ) {
                                            echo '<label class="btn btn-secondary mb-1 mr-1">';
                                            echo '<input type="radio" name="template" id="' . $name . '" value="' . $name . '">';
                                            echo $name . '</label>';
                                        }
                                    echo '</div>
                                </div>
                            </div>';
                        } ?>

                        <hr>

                        <?php if ($_SESSION['everythingFlagPermissions'] <> "deny" || strpos($_SESSION['type'], ":user") == false ) { ?>
                        <div class='searchmons text-center'>
                            <ul>
                                <li><input type='checkbox' name='mon_0' id='mon_0' />
                                    <label for='mon_0' style='padding:15px;' class='text-uppercase'>
					<?php echo i8ln("Apply to all Pokémon"); ?>
                                    </label>
                                </li>
                            </ul>
			</div>
                        <?php } ?>

                        <div class="alert alert-info alert-dismissible fade show" role="alert" id='dvAlertTypeAll'>
			    <?php echo i8ln("Type"); ?> <strong>ALL</strong> <?php echo i8ln("to display all Pokémon"); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

			<!-- Add Search Box -->
                        <input type='hidden' id='search_type' value='mon'>
                        <div class='mb-3' id='dvSearchBox'>
			    <input type='text' class='form-control form-control-lg' id='search' placeholder='<?php echo i8ln("Search") ?>'>
                        </div>

                        <div class='searchmons text-center' id='dvMonsList'>
                            <ul>
                                <!-- Add Empty Div to be used by Ajax to display results -->
                                <div id='display'></div>

                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_mon' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
				<button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>

                    </form>


