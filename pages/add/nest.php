<?php

if ( $disable_nests == "True" ) {
        header("Location: $redirect_url");
        exit();
}

?>

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW NESTS ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <form action='./actions/nests.php' method='POST'>

			<?php if (@$disable_location <> "True") { ?>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="use_areas" id="use_areas" value="areas" checked onclick="areas_add()"><?php echo i8ln("Use Areas"); ?>
                                    </label>
                                    <label class="btn btn-secondary mr-2">
                                        <input type="radio" name="use_areas" id="use_areas" value="distance" onclick="areas_add()"><?php echo i8ln("Set Distance"); ?>
                                    </label>
                                    </div>
                                </div>
                                <div class="input-group mt-2">
                                    <input type="number" id='distance' name='distance' value='0' min='0' max='<?php echo $_SESSION['maxDistance']; ?>' style="display:none;"
                                        class="form-control text-center">
                                    <div class="input-group-append" id="distance_label" style="display:none;">
                                        <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php } else { ?>
                           <input type="hidden" id='distance' name='distance' value='0'>
			<?php } ?>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo i8ln("Spawns/Hour"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    </div>
                                    <input type='number' id='min_spawns' name='min_spawns' size=1 value='0'
                                        min='0' max='100' class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

			<div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">

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
                                        $clean_0_checked = 0;
                                        $clean_1_checked = 0;
                                        if ($all_nests_cleaned == "1") {
                                            $clean_1_checked = 'checked';
                                        } else {
                                            $clean_0_checked = 'checked';
                                        }

                                        ?>
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
                        $templates_locale = $_SESSION['templates'][$type[0]]['nest'][$_SESSION['locale']];
                        $templates_undefined = $_SESSION['templates'][$type[0]]['nest']['%'];
                        $templates_list = array_merge((array)$templates_locale,(array)$templates_undefined);

                        if (count($templates_list) > 1 && $enable_templates == "True" ) {
                            echo '<div class="form-row align-items-center">
                                <div class="col-sm-12 my-1">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Template</div>
                                            </div>
                                        </div>';
                                        foreach ( $templates_list as $key => $name ) {
                                            echo '<label class="btn btn-secondary">';
                                            echo '<input type="radio" name="template" id="' . $name . '" value="' . $name . '">';
                                            echo $name . '</label>';
                                        }
                                    echo '</div>
                                </div>
                            </div>';
                        } ?>


                        <hr>

                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $nests = get_nest_species();
                                    foreach ($nests as &$nest) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='nest_<?php echo $nest; ?>'
                                        id='nest_<?php echo $nest; ?>' />
				    <label for='nest_<?php echo $nest; ?>'>
                                    <?php
                                        if ( $row['form'] <> 0 ) {
                                           $PkmnImg = "$uicons_pkmn/pokemon/" . $nest . "_f" . $row['form'] . ".png";
                                        } else {
                                           $PkmnImg = "$uicons_pkmn/pokemon/" . $nest . ".png";
                                        }
                                    ?>
					<img class='m-2' src=<?php echo $PkmnImg; ?> />
					<br><?php echo i8ln(get_mons($nest)); ?>
                                    </label>
                                </li>
				<?php } ?>

                            </ul>
                        </div>


                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_nests' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
				<button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>

                    </form>


