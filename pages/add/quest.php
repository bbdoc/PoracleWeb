<?php
if ( $disable_quests == "True" ) {
        header("Location: $redirect_url");
        exit();
}

?>

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW QUEST ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <form action='./actions/quests.php' method='POST'>

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
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><?php echo i8ln("Content"); ?></div>
                                        </div>
                                        <input type='text' id='content_add' name='content' maxlength=255 size=50 class="form-control">
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
                                        if ($all_quest_cleaned == "1") {
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
                        $templates_locale = @$_SESSION['templates'][$type[0]]['quest'][$_SESSION['locale']];
                        $templates_undefined = @$_SESSION['templates'][$type[0]]['quest']['%'];
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

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
				    <?php echo i8ln("Select Pokémon Quests you want to add"); ?> (<?php echo i8ln("Pokemons Currently available on Map"); ?>)
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    
                                    $mons =  get_quest_mons();                                    
                                    foreach ($mons as &$mon) {
                                        $arr = explode("_", $mon);
                                        $mon_id = $arr[0];
                                        $form_id = $arr[1];

					$pokemon_name=get_mons($mon_id);  
					$form_name=get_form_name($mon_id,$form_id); 

                                    ?>
                                <li class='text-center'><input type='checkbox' name='mon_<?php echo $mon; ?>'
				id='mon_<?php echo $mon; ?>' />
				    <label for='mon_<?php echo $mon; ?>'>
					<?php if ($form_id <> 0  && $form_name <> 'Normal' ) { $addform = "_f".$form_id; } else { $addform = ""; } ?>
                                        <img class="mb-2" src='<?php echo $uicons_pkmn; ?>/pokemon/<?php echo $mon_id.$addform; ?>.png' />
					<br><?php echo $mon_id; ?><br><?php echo $pokemon_name; ?><br>
                                        <?php if ( $form_name <> "Normal" && $form_name <> "" && $form_id <> "00" ) { ?>
                                           <?php echo $form_name; ?>
					<?php } else { echo "&nbsp"; }  ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

			<!-- Add Search Box -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
				    <?php echo i8ln("Or use search below to add another pokemon"); ?>
                                </li>
                            </ol>
                        </nav>

                        <input type='hidden' id='search_type' value='questmon'>
                        <div class='mb-3' id='dvSearchBox'>
			    <input type='text' class='form-control form-control-lg' id='search' placeholder='<?php echo i8ln("Search"); ?>'>
                        </div>

                        <div class='searchmons text-center' id='dvMonsList'>
                            <ul>
                                <!-- Add Empty Div to be used by Ajax to display results -->
                                <div id='display'></div>

                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
				    <?php echo i8ln("Select Quests Items you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $items =  get_quest_items();
                                    foreach ($items as &$item) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='item_<?php echo $item; ?>'
                                        id='item_<?php echo $item; ?>' />
                                    <label for='item_<?php echo $item; ?>'>
					<img src='<?php echo $uicons_reward; ?>/reward/item/<?php echo $item; ?>.png' />
                                        <?php get_item_name($item); $item_name=get_item_name($item); ?>
                                        <br><?php echo $item_name; ?>
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
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo i8ln("Select Energy Rewards you want to add"); ?>
                                </li>
                            </ol>
                        </nav>

                        <div class='selectionList'>
			    <ul>
                                <li class='text-center'><input type='checkbox' name='energy_0'
                                        id='energy_0' />
                                    <label for='energy_0'>
                                        <img src='<?php echo $uicons_reward; ?>/reward/mega_resource/0.png' />
					<br><br><?php echo i8ln("ALL"); ?>
                                    </label>
                                </li>
                                <?php
                                    $energy_rewards =  get_quest_energy();
                                    foreach ($energy_rewards as &$energy) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='energy_<?php echo $energy; ?>'
                                        id='energy_<?php echo $energy; ?>' />
                                    <label for='energy_<?php echo $energy; ?>'>
					<img src='<?php echo $uicons_reward; ?>/reward/mega_resource/<?php echo $energy; ?>.png' />
					<?php $pokemon_name=get_mons($energy); ?>
                                        <br><?php echo str_pad($energy, 3, "0", STR_PAD_LEFT); ?><br><?php echo $pokemon_name; ?>
                                    </label>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo i8ln("Select Candy Rewards you want to add"); ?>
                                </li>
                            </ol>
                        </nav>

                        <div class='selectionList'>
                            <ul>
                                <li class='text-center'><input type='checkbox' name='candy_0'
                                        id='candy_0' />
                                    <label for='candy_0'>
                                        <img src='./img/candy/0.png'/>
                                        <br><br><?php echo i8ln("ALL"); ?>
                                    </label>
                                </li>
                                <?php
                                    $candy_rewards =  get_quest_candy();
                                    foreach ($candy_rewards as &$candy) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='candy_<?php echo $candy; ?>'
                                        id='candy_<?php echo $candy; ?>' />
                                    <label for='candy_<?php echo $candy; ?>'>
                                        <img src='./img/candy/<?php echo $candy; ?>.png' />
                                        <?php $pokemon_name=get_mons($candy); ?>
                                        <br><?php echo str_pad($candy, 3, "0", STR_PAD_LEFT); ?><br><?php echo $pokemon_name; ?>
                                    </label>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
                            <input class="btn btn-primary" type='submit' name='add_quest' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
                                <button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>



                    </form>

