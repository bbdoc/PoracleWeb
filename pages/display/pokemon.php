
<?php

$sql_base = "select count(*) count FROM monsters WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "' ";

$sql = $sql_base."AND pokemon_id =0";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $genall = $row['count']; }

$sql = $sql_base."AND pokemon_id between 1 and 151";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen1 = $row['count']; }

$sql = $sql_base."AND pokemon_id between 152 and 251";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen2 = $row['count']; }

$sql = $sql_base."AND pokemon_id between 252 and 386";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen3 = $row['count']; }

$sql = $sql_base."AND pokemon_id between 387 and 493";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen4 = $row['count']; }

$sql = $sql_base."AND pokemon_id between 494 and 649";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen5 = $row['count']; }

$sql = $sql_base."AND pokemon_id between 650 and 721";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen6 = $row['count']; }

$sql = $sql_base."AND pokemon_id between 722 and 809";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen7 = $row['count']; }

$sql = $sql_base."AND pokemon_id >= 810";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $gen8 = $row['count']; }

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


                        <!-- GEN SELECTOR -->

                        <?php

                           if (!isset($_GET['gen']) || $_GET['gen'] == "") { $_GET['gen'] = "all"; }

                           if ( @$_GET['gen'] == "all" ) { $gen_selector = "AND pokemon_id = 0"; }
                           if ( @$_GET['gen'] == 1 ) { $gen_selector = "AND pokemon_id between 1 and 151"; }
                           if ( @$_GET['gen'] == 2 ) { $gen_selector = "AND pokemon_id between 152 and 251"; }
                           if ( @$_GET['gen'] == 3 ) { $gen_selector = "AND pokemon_id between 252 and 386"; }
                           if ( @$_GET['gen'] == 4 ) { $gen_selector = "AND pokemon_id between 387 and 493"; }
                           if ( @$_GET['gen'] == 5 ) { $gen_selector = "AND pokemon_id between 494 and 649"; }
                           if ( @$_GET['gen'] == 6 ) { $gen_selector = "AND pokemon_id between 650 and 721"; }
                           if ( @$_GET['gen'] == 7 ) { $gen_selector = "AND pokemon_id between 722 and 809"; }
			   if ( @$_GET['gen'] == 8 ) { $gen_selector = "AND pokemon_id >= 810"; }

                        ?>
                        
                        <?php 

                        // Count Trackings
                        $sql = "select * FROM monsters WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
                        $result = $conn->query($sql);

			// Show ALL Mons if less than 50 trackings
			if ( $result->num_rows <= 50 ) { $gen_selector = ""; }

                        // Only Show Gen Selector if More than 50 trackings
                        if ( $result->num_rows > 50 ) {

                        ?>

                        <nav aria-label="Gen Selector">
                          <ul class="pagination justify-content-left ml-1">
			    <li class="page-item <?php if (@$_GET['gen'] == "all") { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=all">
			    <center><?php echo i8ln("ALL"); ?><br><small><?php echo $genall; ?></small></center>
                            </a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 1) { echo "active";}; ?>">
			    <a class="page-link gen-link" href="?type=display&page=pokemon&gen=1"><center>G1<br><small><?php echo $gen1; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 2) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=2"><center>G2<br><small><?php echo $gen2; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 3) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=3"><center>G3<br><small><?php echo $gen3; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 4) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=4"><center>G4<br><small><?php echo $gen4; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 5) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=5"><center>G5<br><small><?php echo $gen5; ?></small></center></a></li>
			    <li class="page-item <?php if (@$_GET['gen'] == 6) { echo "active";}; ?>">
			    <a class="page-link gen-link" href="?type=display&page=pokemon&gen=6"><center>G6<br><small><?php echo $gen6; ?></small></center></a></li>
                            <li class="page-item <?php if (@$_GET['gen'] == 7) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=7"><center>G7<br><small><?php echo $gen7; ?></small></center></a></li>
                            <li class="page-item <?php if (@$_GET['gen'] == 8) { echo "active";}; ?>">
                            <a class="page-link gen-link" href="?type=display&page=pokemon&gen=8"><center>G8<br><small><?php echo $gen8; ?></small></center></a></li>
                          </ul>
			</nav>

                        <?php } ?>

                        <?php echo @$config_alarm; ?>
                        
                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Check if User is already tracking something

                                $sql = "select count(*) count FROM monsters WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'";
                                $result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$num_mon_tracked = $row['count'];
				}

                                // Show Monsters Alarms         

				$sql = "select * FROM monsters WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "' " . @$gen_selector ." 
					ORDER BY pokemon_id, form"; 
                                $result = $conn->query($sql);
				if ($num_mon_tracked == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
				} else if ($result->num_rows == 0 && isset($_GET['allgen']))
				{ 
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm for ALL Pokemons, Please select a Gen!");
                                   echo "</div>";
				} else if ($result->num_rows == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarms for this Generation!");
                                   echo "</div>";
				} 

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index

                                    $pkm_unique_id = "mon_" . $row['uid'];

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

                                                            if ($row['distance'] <> '0') {
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

						    <?php if ( $row['distance'] > 0 ) { ?>
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
                                                            if ($row['min_iv'] <> '-1' || $row['max_iv'] <> '100') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("IV"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['min_iv']; ?>
                                                            -
                                                            <?php echo $row['max_iv']; ?></span>
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
                                                            if ($row['min_cp'] <> '0' || $row['max_cp'] <> '9000') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("CP"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['min_cp']; ?>
                                                            -
                                                            <?php echo $row['max_cp']; ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['min_level'] <> '0' || $row['max_level'] < '40') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("LEVEL"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['min_level']; ?>
                                                            - <?php echo $row['max_level']; ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['atk'] <> '0' || $row['def'] <> '0' || $row['sta'] <> '0' || $row['max_atk'] <> '15' || $row['max_def'] <> '15' || $row['max_sta'] <> '15') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("STATS"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['atk'] . "/" . $row['def'] . "/" . $row['sta']; ?>
                                                            -
                                                            <?php echo $row['max_atk'] . "/" . $row['max_def'] . "/" . $row['max_sta']; ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['pvp_ranking_league'] > 0) {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
							<?php 
                                                           if ($row['pvp_ranking_league'] == 500) { echo i8ln("LITTLE"); }
                                                           if ($row['pvp_ranking_league'] == 1500) { echo i8ln("GREAT"); }
                                                           if ($row['pvp_ranking_league'] == 2500) { echo i8ln("ULTRA"); }
                                                        ?>
                                                        <span
							    class="badge badge-primary badge-pill">top
							    <?php if ( $row['pvp_ranking_worst'] <> $row['pvp_ranking_best'] ) { echo $row['pvp_ranking_best']."-";} ?>
                                                            <?php echo $row['pvp_ranking_worst']; ?>
                                                            @<?php echo $row['pvp_ranking_min_cp']; ?></span>
						    </li>
                                                    <?php
                                                            }
                                                            if ($row['pvp_ranking_cap'] <> '0') {
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
                                                            if ($row['min_weight'] <> '0' || $row['max_weight'] <> '9000000') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("WEIGHT"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['min_weight']; ?>
                                                            - <?php
                                                                            if ($row['max_weight'] == '9000000') {
                                                                                echo "MAX";
                                                                            } else {
                                                                                echo $row['max_weight'];
                                                                            }
                                                                            ?>
                                                        </span>
                                                    </li>
                                                    <?php
                                                            }
                                                            ?>

                                                    <?php
                                                            if ($row['gender'] <> '0') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("GENDER"); ?>
                                                        <span class="badge badge-primary badge-pill">
                                                            <?php 
								    if ($row['gender'] == '1') {  echo i8ln("Male"); } 
								    if ($row['gender'] == '2') {  echo i8ln("Female"); } 
                                                        ?>
                                                        </span>
                                                    </li>
                                                    <?php }
                                                        if ($row['ping'] <> '') {
                                                    ?>
                                                    <li
                                                        class="list-group-item justify-content-between align-items-center">
                                                        <?php echo i8ln("PING"); ?><br>
                                                        <div class="bg-secondary text-break text-white p-1 rounded">
                                                            <span class="small"><?=$row['ping']?></span>
                                                        </div>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php
                                                        if ($row['clean'] == '1' && $all_mon_cleaned == '0') {
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

					   	    if ( in_array($row['template'], $templates_list ) )
						    {
							    $template = $row['template'];
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
                                                    data-target="#<?php echo $pkm_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $pkm_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT MONSTER Modal -->
                            <div class="modal fade" id="<?php echo $pkm_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $pkm_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_pokemons_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE MONSTER Modal -->
                            <?php $form=get_form_name($row['pokemon_id'],$row['form']); ?>
                            <div class="modal fade" id="<?php echo $pkm_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $pkm_unique_id ?>DeleteModalLabel"
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

