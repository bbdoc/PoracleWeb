
<?php

if ( isset($_POST['search']) ) { $_SESSION['search'] = $_POST['search']; unset($_POST['search']); }

// Search: get_matching_ids() returns Pokemon IDs from JSON; keep them as integers.
$search_ids = null;
if ( !empty($_SESSION['search']) )
{
	$matching_ids = get_matching_ids($_SESSION['search']);
	$matching_ids = array_filter($matching_ids, 'is_numeric');
	$matching_ids = array_map('intval', $matching_ids);
	if (!empty($matching_ids)) {
		$search_ids = array_values($matching_ids);
	}
}

// Label for a range with open ends: "a - b", "a+" or "0 - b" (floor) / "<= b"
function pokemon_range_label($min, $max, $floor = false)
{
	if ($min !== null && $max !== null) { return $min . " - " . $max; }
	if ($min !== null) { return $min . "+"; }
	return $floor ? "0 - " . $max : "&le; " . $max;
}

$all = poracle_filter_by_ids(poracle_rules('pokemon'), $search_ids);

// Count each generation
$gen_counts = array();
foreach (array('all', '1', '2', '3', '4', '5', '6', '7', '8', '9') as $g) {
	$gen_counts[$g] = count(array_filter($all, function ($r) use ($g) { return poracle_pokemon_gen($r['pokemon_id']) === $g; }));
}
$genall = $gen_counts['all'];
$gen1 = $gen_counts['1'];
$gen2 = $gen_counts['2'];
$gen3 = $gen_counts['3'];
$gen4 = $gen_counts['4'];
$gen5 = $gen_counts['5'];
$gen6 = $gen_counts['6'];
$gen7 = $gen_counts['7'];
$gen8 = $gen_counts['8'];
$gen9 = $gen_counts['9'];

?>


                <div class="tab-content mb-5" id="pills-tab-homeContent">
                    <?php if (@$disable_mons <> "True") { ?>
                    <div class="tab-pane fade show active" id="pills-mons" role="tabpanel"
                        aria-labelledby="pills-mons-tab">

			<!-- Top Quick Links  -->
			<?php include "include/toplinks.php"; ?>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
				<h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("POKÉMON TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_mon_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL Monsters"); ?></strong>!
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <?php
                                }
                                ?>
                        </div>

                        <div class="row">
                            <div class="row no-gutters align-items-center p-3">
                                <a href="./?type=add&page=pokemon" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllMonsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL"); ?></span>
				</a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceMonsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL MONS Modal -->
                        <div class="modal fade" id="deleteAllMonsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllMonsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllMonsModalTitle">
                                            <?php echo i8ln("Delete ALL tracked Mons?"); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Pokémon Alarms and cannot be undone, are you sure ?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/monsters.php?action=delete_all_mons"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

			<!-- UPDATE MONS DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceMonsModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceMonsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
                                        <?php include "./modal/distance_pokemons_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <form action='#' method='POST' id='search'>
                          <div class="form-row align-items-center">
                            <div style="width:300px; margin-left:10px;">
			    <input type="text" class="form-control" id="search" name="search" minlength="3" value='<?php echo @$_SESSION['search']; ?>' 
                                   placeholder="<?php echo i8ln("Search by name, type or ID"); ?>">
                            </div>
                            <div class="col-auto">
                              <button type='submit' id='submit' name='submit' class='btn btn-light' style='margin-top:-15px;'><i class='fas fa-search'></i></button>
                            </div>
                          </div>
                        </form>

                        <!-- GEN SELECTOR -->

                        <?php

                           if (!isset($_GET['gen']) || $_GET['gen'] == "") { $_GET['gen'] = "all"; }
                           $gen_selector = (string) $_GET['gen'];
                           if (!in_array($gen_selector, array('all', '1', '2', '3', '4', '5', '6', '7', '8', '9'), true)) { $gen_selector = ""; }
                        ?>
                        
                        <?php

                        // Show ALL Mons if less than 50 trackings
                        $num_mon_total = count($all);
                        if ( $num_mon_total <= 50 ) { $gen_selector = ""; }

                        // Only Show Gen Selector if More than 50 trackings
                        if ( $num_mon_total > 50 ) {

                        ?>

                        <nav aria-label="Gen Selector">
                          <ul class="pagination justify-content-left ml-1">
			    <li class="page-item <?php if (@$_GET['gen'] == "all") { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=all">
			    <center><small><b><?php echo i8ln("ALL"); ?></b><br><?php echo $genall; ?></small></center>
                            </a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 1) { echo "active";}; ?>">
			    <a class="page-link gen-link" href="?type=display&page=pokemon&gen=1"><center><small><b>G1</b><br><?php echo $gen1; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 2) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=2"><center><small><b>G2</b><br><?php echo $gen2; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 3) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=3"><center><small><b>G3</b><br><?php echo $gen3; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 4) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=4"><center><small><b>G4</b><br><?php echo $gen4; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 5) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=5"><center><small><b>G5</b><br><?php echo $gen5; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 6) { echo "active";}; ?>">
			    <a class="page-link gen-link" href="?type=display&page=pokemon&gen=6"><center><small><b>G6</b><br><?php echo $gen6; ?></small></center></a></li>
                            <li class="page-item <?php if (@$_GET['gen'] == 7) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=7"><center><small><b>G7</b><br><?php echo $gen7; ?></small></center></a></li>
                            <li class="page-item <?php if (@$_GET['gen'] == 8) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=8"><center><small><b>G8</b><br><?php echo $gen8; ?></small></center></a></li>
                            <li class="page-item <?php if (@$_GET['gen'] == 9) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=9"><center><small><b>G9</b><br><?php echo $gen9; ?></small></center></a></li>
                          </ul>
			</nav>

                        <?php } ?>

                        <?php echo @$config_alarm; ?>
                        
                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Check if User is already tracking something

				$num_mon_tracked = count($all);

                                // Show Monsters Alarms

				$mons = $all;
				if ( $gen_selector !== "" ) {
					$mons = array_values(array_filter($all, function ($r) use ($gen_selector) { return poracle_pokemon_gen($r['pokemon_id']) === $gen_selector; }));
				}
				usort($mons, function ($a, $b) {
					$c = (int) ($a['pokemon_id'] ?? 0) - (int) ($b['pokemon_id'] ?? 0);
					return $c !== 0 ? $c : (int) ($a['form'] ?? 0) - (int) ($b['form'] ?? 0);
				});
				if ($num_mon_tracked == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
				} else if (count($mons) == 0 && isset($_GET['allgen']))
				{ 
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm for ALL Pokemons, Please select a Gen!");
                                   echo "</div>";
				} else if (count($mons) == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarms for this Generation!");
                                   echo "</div>";
				} 

                                foreach ($mons as $row) {
                                    $row['pokemon_id'] = (int) ($row['pokemon_id'] ?? 0);
                                    $row['form'] = (int) ($row['form'] ?? 0);

                                    // Build a Unique Index

                                    $unique_id = "mon_" . $row['uid'];

                                    // Check Images only if Form <> Normal and Substitude if necessary
                                    if ($row['form'] <> 0) {
                                        $PkmnImg = "$uicons_pkmn/pokemon/" . $row['pokemon_id'] . "_f" . $row['form'] . ".png";
					if (false === @file_get_contents("$PkmnImg", 0, null, 0, 1)) {
				            $PkmnImg = "$uicons_pkmn/pokemon/" . $row['pokemon_id'] . ".png";
                                        }
                                    } else {
                                        $PkmnImg = "$uicons_pkmn/pokemon/" . $row['pokemon_id'] .".png";
                                    }
				    $PkmnImg_50 = "<img loading=lazy width=50 src='$PkmnImg'>";
				    $PkmnImg_100 = "<img loading=lazy width=100 src='$PkmnImg'>";

                                ?>

                            <!-- Card -->
                            <div class="col-xl-3 col-sm-4 col-6 mb-3 cardlist">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <?php
                                                        if ($row['pokemon_id'] == '0') {
                                                        ?>
                                                <div class="h5 mb-0 mt-2 font-weight-bold text-gray-800 text-center"
                                                    style="height: 65px;">
                                                    <font style='font-size:32px;'><?php echo i8ln("ALL"); ?></font>
                                                </div>
                                                <?php
                                                        } else {
                                                        ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <?php echo $PkmnImg_50; ?></div>
                                                <?php $pokemon_name=get_mons($row['pokemon_id']); ?>
                                                <?php  
                                                           $form_name = get_form_name($row['pokemon_id'], $row['form']);
                                                    ?>
                                                <div>
                                                    <span
                                                        class="badge badge-primary badge-pill w-100"><?php echo $row['pokemon_id']." | ".$pokemon_name." ".i8ln($form_name); ?></span>
                                                </div>

                                                <?php
                                                        }
                                                        ?>
                                                <ul class="list-group mt-2">
                                                    <?php

                                                            if (!empty($row['distance'])) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
							<?php echo i8ln("DISTANCE"); ?>
							<?php if ( @$distance_map <> "True" ) { ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
							</span>
                                                        <?php } else { ?>
                                                        <a href="#DistanceShowPokemons" data-toggle="modal" data-target="#DistanceShowPokemons_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
							</a>
                                                        <?php } ?>
						    </li>

						    <?php if ( !empty($row['distance']) ) { ?>
                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowPokemons_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowPokemonsTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <?php include "./modal/distance_show_modal.php"; ?>
                                                                </div>
                                                            </div>
                                                        </div>
						    </div>
                                                    <?php } ?>

                                                    <?php
                                                            }
                                                            if ($row['min_iv'] !== null || $row['max_iv'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("IV"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo pokemon_range_label($row['min_iv'], $row['max_iv'], true); ?></span>
                                                    </li>
                                                    <?php
                                                            } else {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("IV"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo i8ln("ALL"); ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['min_cp'] !== null || $row['max_cp'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("CP"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo pokemon_range_label($row['min_cp'], $row['max_cp'], true); ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['min_level'] !== null || $row['max_level'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("LEVEL"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo pokemon_range_label($row['min_level'], $row['max_level']); ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['atk'] !== null || $row['def'] !== null || $row['sta'] !== null || $row['max_atk'] !== null || $row['max_def'] !== null || $row['max_sta'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("STATS"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo ($row['atk'] ?? "*") . "/" . ($row['def'] ?? "*") . "/" . ($row['sta'] ?? "*"); ?>
                                                            -
                                                            <?php echo ($row['max_atk'] ?? "*") . "/" . ($row['max_def'] ?? "*") . "/" . ($row['max_sta'] ?? "*"); ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['pvp_ranking_league'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
							<?php 
                                                           if ($row['pvp_ranking_league'] == 500) { echo i8ln("LITTLE"); }
                                                           if ($row['pvp_ranking_league'] == 1500) { echo i8ln("GREAT"); }
                                                           if ($row['pvp_ranking_league'] == 2500) { echo i8ln("ULTRA"); }
                                                        ?>
                                                        <span
							    class="badge badge-primary badge-pill"><?php if ( $row['pvp_ranking_worst'] !== null ) { ?>top
							    <?php if ( $row['pvp_ranking_best'] !== null && $row['pvp_ranking_worst'] <> $row['pvp_ranking_best'] ) { echo $row['pvp_ranking_best']."-";} ?>
                                                            <?php echo $row['pvp_ranking_worst']; ?>
                                                            <?php } else { echo i8ln("ANY"); } ?>
                                                            <?php if ( $row['pvp_ranking_min_cp'] !== null ) { ?>@<?php echo $row['pvp_ranking_min_cp']; ?><?php } ?></span>
						    </li>
                                                    <?php
                                                            }
                                                            if ($row['pvp_ranking_cap'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("PvP Cap"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['pvp_ranking_cap']; ?>
                                                        </span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['min_weight'] !== null || $row['max_weight'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("WEIGHT"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo pokemon_range_label($row['min_weight'], $row['max_weight'], true); ?>
                                                        </span>
                                                    </li>
                                                    <?php
                                                            }
                                                            ?>

                                                    <?php
                                                            if ($row['gender'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("GENDER"); ?>
                                                        <span class="badge badge-primary badge-pill">
                                                            <?php 
								    if (poracle_gender_id($row['gender']) == 1) {  echo i8ln("Male"); } 
								    if (poracle_gender_id($row['gender']) == 2) {  echo i8ln("Female"); } 
								    if (poracle_gender_id($row['gender']) == 3) {  echo i8ln("Genderless"); } 
                                                        ?>
                                                        </span>
                                                    </li>
                                                    <?php }
                                                            if ($row['size'] !== null) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("SIZE"); ?>
                                                        <span class="badge badge-primary badge-pill">
                                                            <?php
								    $size_name=get_size_name($row['size']);
								    echo $size_name;
								    if ( $row['max_size'] === null )
								    {
									    echo "+";
								    }
								    else if ( $row['max_size'] <> $row['size'] )
								    {
									    $max_size_name=get_size_name($row['max_size']);
									    echo "-".$max_size_name;
								    }
                                                        ?>
                                                        </span>
                                                    </li>
                                                    <?php }

                      					    if (!empty($row['ping'])) {
                                                    ?>
                                                    <li
                                                        class="list-group-item justify-content-between align-items-center">
                                                        <?php echo i8ln("PING"); ?><br>
                                                        <div class="bg-secondary text-break text-white p-1 rounded">
                                                            <span class="small"><?=htmlspecialchars($row['ping'])?></span>
                                                        </div>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php
                                                        if (!empty($row['clean']) && $all_mon_cleaned == '0') {
                                                        ?>
                                                <div class="mt-1">
                                                    <span
                                                        class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                </div>
                                                <?php
							}

							
                                                if ( $enable_templates == "True" ) {
                                                ?>

						<div class="mt-1">

                                                    <?php 

                                                    $type = explode(":", $_SESSION['type'], 2); 
						    if ( $type[0] == "webhook" ) { $type[0] = "discord"; }
                                                    $templates_locale = @$_SESSION['templates'][$type[0]]['monster'][$_SESSION['locale']];
                                                    $templates_undefined = @$_SESSION['templates'][$type[0]]['monster']['%'];
                                                    $templates_list = array_merge((array)$templates_locale,(array)$templates_undefined);

					   	    $tpl = $row['template'] ?? $_SESSION['defaultTemplateName'];
					   	    if ( in_array($tpl, $templates_list ) )
						    {
							    $template = $tpl;
						    }
						    else 
						    {
							    $template = "UNKNOWN";
						    }

                                                    ?>

						    <span class="badge badge-pill badge-info w-100">Template: <?php echo $template; ?></span>

						</div>

                                                <?php } ?>

                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center mt-2">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT MONSTER Modal -->
                            <div class="modal fade" id="<?php echo $unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_pokemons_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE MONSTER Modal -->
                            <?php $form=get_form_name($row['pokemon_id'],$row['form']); ?>
                            <div class="modal fade" id="<?php echo $unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_monsters_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }
                                ?>
                        </div>
                        <!-- Content Row -->

                    </div>
                    <?php
                    } // End of Mons Disable 
                    ?>

