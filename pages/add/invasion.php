<?php

if ( $disable_invasions == "True" ) {
        header("Location: $redirect_url");
        exit();
}

$grunt_type_list="bug,dark,dragon,electric,fairy,fighting,fire,flying,ghost,grass,ground,ice,normal,poison,psychic,rock,steel,water";
$grunt_type_list.=",arlo,cliff,giovanni,sierra";

?>

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW INVASIONS ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <form action='./actions/invasions.php' method='POST'>

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
                                    <input type="number" id='distance' name='distance' value='0' min='0' style="display:none;"
                                        class="form-control text-center">
                                    <div class="input-group-append" id="distance_label" style="display:none;">
                                        <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php } else { ?>
                           <input type="hidden" id='distance' name='distance' value='0' min='0'>
			<?php } ?>

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
                                        if ($all_invasion_cleaned == "1") {
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

                        <?php if (isset($allowed_templates["invasions"])) {
                            echo '<div class="form-row align-items-center">
                                <div class="col-sm-12 my-1">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Template</div>
                                            </div>
                                        </div>';
                                        foreach ( $allowed_templates["invasions"] as $key => $name ) {
                                            echo '<label class="btn btn-secondary">';
                                            echo '<input type="radio" name="template" id="' . $key . '" value="' . $key . '">';
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
                                    $grunts = explode(',', $grunt_type_list);
                                    foreach ($grunts as &$grunt) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='grunt_<?php echo $grunt; ?>'
                                        id='grunt_<?php echo $grunt; ?>' />
                                    <label for='grunt_<?php echo $grunt; ?>'>
                                        <img class='m-2' src='./grunts/<?php echo $grunt; ?>.png' />
					<br><?php echo ucfirst(i8ln($grunt)); ?>
                                    </label>
                                </li>
				<?php } ?>
                                <li class='text-center'><input type='checkbox' name='grunt_mixed'
                                        id='grunt_mixed'>
                                    <label for='grunt_mixed'>
                                        <img class='m-2' src='./grunts/James.png' />
                                        <img class='m-2' src='./grunts/Jessie.png' />
                                        <br><?php echo i8ln("Mixed"); ?>
                                    </label>
                                </li>

                            </ul>
                        </div>


                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_invasions' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
				<button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>

                    </form>

