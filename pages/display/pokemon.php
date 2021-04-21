
                <div class="tab-content mb-5" id="pills-tab-homeContent">
                    <?php if (@$disable_mons <> "True") { ?>
                    <div class="tab-pane fade show active" id="pills-mons" role="tabpanel"
                        aria-labelledby="pills-mons-tab">

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

                           if (!isset($_GET['gen1']) && !isset($_GET['gen2']) && !isset($_GET['gen3'])
                                   && !isset($_GET['gen4']) && !isset($_GET['gen5']) && !isset($_GET['gen6']) )
			   { 
				$_GET['allgen'] = "active";

                                // If Tracking More than 50 pokemons, show only ALL
                                $sql = "select * FROM monsters WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
                                $result = $conn->query($sql);
                                if ( $result->num_rows > 50 ) {  $gen_selector = "AND pokemon_id = 0";; }
			   }
                           if ( @$_GET['gen1'] == "active" ) { $gen_selector = "AND pokemon_id between 1 and 151"; }
                           if ( @$_GET['gen2'] == "active" ) { $gen_selector = "AND pokemon_id between 152 and 251"; }
                           if ( @$_GET['gen3'] == "active" ) { $gen_selector = "AND pokemon_id between 252 and 386"; }
                           if ( @$_GET['gen4'] == "active" ) { $gen_selector = "AND pokemon_id between 387 and 493"; }
                           if ( @$_GET['gen5'] == "active" ) { $gen_selector = "AND pokemon_id between 494 and 649"; }
                           if ( @$_GET['gen6'] == "active" ) { $gen_selector = "AND pokemon_id >= 650"; }

                        ?>
                        
                        <nav aria-label="Gen Selector">
                          <ul class="pagination justify-content-left ml-1">
			    <li class="page-item <?php echo @$_GET['allgen']; ?>"><a class="page-link" href="?allgen=active">
			    <center><?php echo i8ln("ALL"); ?></center></a></li>
                            <li class="page-item <?php echo @$_GET['gen1']; ?>"><a class="page-link" href="?gen1=active"><center>G1</center></a></li>
                            <li class="page-item <?php echo @$_GET['gen2']; ?>"><a class="page-link" href="?gen2=active"><center>G2</center></a></li>
                            <li class="page-item <?php echo @$_GET['gen3']; ?>"><a class="page-link" href="?gen3=active"><center>G3</center></a></li>
                            <li class="page-item <?php echo @$_GET['gen4']; ?>"><a class="page-link" href="?gen4=active"><center>G4</center></a></li>
                            <li class="page-item <?php echo @$_GET['gen5']; ?>"><a class="page-link" href="?gen5=active"><center>G5</center></a></li>
                            <li class="page-item <?php echo @$_GET['gen6']; ?>"><a class="page-link" href="?gen6=active"><center>G6</center></a></li>
                          </ul>
			</nav>

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
                                    $PkmnImg = "$imgUrl/pokemon_icon_" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "_" . str_pad($row['form'], 2, "0", STR_PAD_LEFT) . ".png";
                                    if ($row['form'] <> 0) {
                                        if (false === @file_get_contents("$PkmnImg", 0, null, 0, 1)) {
                                            $PkmnImg_50 = "<font style='font-size:42px;'><strong>" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "</strong></font>";
                                            $PkmnImg_100 = "<font size=8><strong>" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "</strong></font>";
                                        } else {
                                            $PkmnImg_50 = "<img loading=lazy width=50 src='$PkmnImg'>";
                                            $PkmnImg_100 = "<img loading=lazy width=100 src='$PkmnImg'>";
                                        }
                                    } else {
                                        $PkmnImg_50 = "<img loading=lazy width=50 src='$PkmnImg'>";
                                        $PkmnImg_100 = "<img loading=lazy width=100 src='$PkmnImg'>";
                                    }

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
                                                        class="badge badge-primary badge-pill w-100"><?php echo $row['pokemon_id']." | ".$pokemon_name." ".$form_name; ?></span>
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
                                                            if ($row['min_level'] <> '0' || $row['max_level'] <> '40') {
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
                                                            if ($row['great_league_ranking'] <> '4096' || $row['great_league_ranking_min_cp'] <> '0') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("GREAT"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill">top<?php echo $row['great_league_ranking']; ?>
                                                            @<?php echo $row['great_league_ranking_min_cp']; ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['ultra_league_ranking'] <> '4096') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("ULTRA"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill">top<?php echo $row['ultra_league_ranking']; ?>
                                                            @<?php echo $row['ultra_league_ranking_min_cp']; ?></span>
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
                                                        if (isset($allowed_templates["mons"])) {
                                                ?>
                                                <div class="mt-1">
                                                    <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["mons"]) ? $allowed_templates["mons"][$row['template']] : 'UNKNOWN'; ?></span>
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

