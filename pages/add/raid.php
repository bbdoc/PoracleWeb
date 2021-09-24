<?php
if ( $disable_raids == "True" ) {
        header("Location: $redirect_url");
        exit();
}

?>

<body id="page-top">


                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW RAID ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <form action='./actions/raids.php' method='POST'>

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
                                        if ($all_raid_cleaned == "1") {
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
                        $templates_locale = @$_SESSION['templates'][$type[0]]['raid'][$_SESSION['locale']];
                        $templates_undefined = @$_SESSION['templates'][$type[0]]['raid']['%'];
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

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page"><?php echo i8ln("Select Egg Levels you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $eggs = explode(',', "1,3,5,6");
                                    foreach ($eggs as &$egg) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='egg_<?php echo $egg; ?>'
                                        id='egg_<?php echo $egg; ?>' />
                                    <label for='egg_<?php echo $egg; ?>'>
                                        <img src='<?php echo $uicons_raid; ?>/raid/egg/<?php echo $egg; ?>.png' />
					<br><?php echo i8ln("Eggs"); ?><br><?php echo i8ln("Level"); ?> <?php echo $egg; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page"><?php echo i8ln("Select Raid Levels you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $raids = explode(',', "1,3,5,6");
                                    foreach ($raids as &$raid) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='raid_<?php echo $raid; ?>'
                                        id='raid_<?php echo $raid; ?>' />
                                    <label for='raid_<?php echo $raid; ?>'>
                                        <img src='./img/raid_<?php echo $raid; ?>.png' />
					<br><?php echo i8ln("Raids"); ?><br><?php echo i8ln("Level"); ?> <?php echo $raid; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page"><?php echo i8ln("Select the Raid Bosses you want to add"); ?>
                                </li>
                            </ol>
			    </nav>   
                        <div class='selectionList'>
                            <ul>
                                <?php

				    if (isset($source_raid_bosses) && $source_raid_bosses == "JSON" ) { 
					    $bosses = get_raid_bosses_json();
				    } else {
					    $bosses = get_raid_bosses();
				    }

                                    foreach ($bosses as $key => $boss) {
                                        $arr = explode("_", $boss);
                                        $boss_id = $arr[0];
                                        $boss_form = $arr[1];
                                        if (isset($arr[2])) { $boss_mega = $arr[2]; }
                                        if (@$boss_mega == 2) {
                                            $mega_name = "Mega X";
                                        } else if (@$boss_mega == 3) {
                                            $mega_name = "Mega Y";
                                        } else if (@$boss_mega == 1) {
                                            $mega_name = "Mega";
                                        } else {
                                            $mega_name = "";
                                        }
                                        $pokemon_name = get_mons($boss_id);
                                    ?>

                                <li class='text-center'><input type='checkbox'
                                        name='mon_<?php echo $boss_id; ?>_<?php echo $boss_form; ?>'
                                        id='mon_<?php echo $boss_id; ?>_<?php echo $boss_form; ?>' />
					<label for='mon_<?php echo $boss_id; ?>_<?php echo $boss_form; ?>'>
                                        <?php 
                                           if ($boss_form <> 0 ) { $addform = "_f".$boss_form; } else { $addform = ""; }
                                           if (@$boss_mega <> 0 ) { $addevolution = "_e".$boss_mega; } else { $addevolution = ""; }
				  	   $img="$uicons_pkmn/pokemon/" . $boss_id . $addevolution . $addform . ".png"; 
					   if (false === @file_get_contents("$img", 0, null, 0, 1)) { 
					      $img="$uicons_pkmn/pokemon/" . $boss_id . ".png";
					   }
                                        ?>
                                        <img src='<?php echo $img; ?>' />
                                        <br>
                                        <?php echo str_pad($boss_id, 3, "0", STR_PAD_LEFT); ?>
					<br>
                                        <?php 
                                             $form_name = get_form_name($boss_id, $boss_form);
                                             if ( $form_name == "Normal" ) { $form_name = ""; }
					     echo $pokemon_name." ".$form_name; 
					     echo "<br>".$mega_name;
                                        ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_raid' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
				<button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>

                    </form>


