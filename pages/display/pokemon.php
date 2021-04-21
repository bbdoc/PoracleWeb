
                <!-- Content Row -->
                <div class="row">

		    <!-- PROFILE Card -->
		    <?php if (@$disable_profiles <> "True") { ?>

                    <div class="col-xl-12 col-md-12">
			<!-- Current Profile -->

			<div class="card shadow mb-4">
                            <div class="card-header py-3">
				<div class="row d-flex justify-content-between align-items-center pl-3 pr-3">
				   <h6 class="m-0 font-weight-bold text-dark"><?php echo i8ln("Profile"); ?>: 
				   <strong><?php echo $_SESSION['profile_name']; ?></strong>
				   </h6>
				   <?php if ( $_SESSION['profile'] == $_SESSION['current_profile'] ) { ?>
				   <span class="badge badge-success badge-pill ml-2" style="padding:5px; padding-right:15px; padding-left:15px;">
				      <?php echo i8ln("Active"); ?>
                                   </span>
				   <?php } else { ?>
                                   <span class="badge badge-info badge-pill ml-2" style="padding:5px; padding-right:15px; padding-left:15px;">
                                      <?php echo i8ln("Not Active"); ?>
                                   </span>
                                   <?php } ?>
                               </div>
                            </div>

                            <div class="card-body">
			      <div class="row no-gutters align-items-center">
                                <div class="text-center w-100">
				   <button type="button" class="btn btn-danger btn-circle btn-md m-1" 
                                       data-toggle="modal" data-target="#DeleteProfile">
                                       <i class="fas fa-trash"></i>
				   </button>
                                   <!--
				   <button type="button" class="btn btn-info btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#ScheduleProfile">
                                       <i class="fas fa-clock"></i>
				   </button>
                                   -->
				   <button type="button" class="btn btn-success btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#AddProfile">
                                       <i class="fas fa-plus-circle"></i>
				   </button>
				   <button type="button" class="btn btn-success btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#RenameProfile">
                                       <i class="fas fa-edit"></i>
				   </button>
                                   <?php if ( $_SESSION['number_of_profiles'] > 1 ) { ?>
				   <button type="button" class="btn btn-success btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#SwitchProfile">
                                       <i class="fas fa-random"></i>
				   </button>
                                   <?php } ?>

		   		 </div>
		              </div>
                            </div>
                        </div>
		    </div>
                    <?php } ?>

                    <!-- PROFILE EDIT MODAL -->
                    <?php include "./modal/edit_profile_modal.php"; ?>

                    <!-- LOCATION AND AREAS Card -->
                    <?php if (@$disable_areas."-".@$disable_location <> "True-True" ) { ?>
                    <div class="col-xl-12 col-md-12">
                        <!-- Areas & Locations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row d-flex justify-content-between align-items-center pl-3 pr-3">

                                    <?php if (@$disable_areas <> "True") { ?>
                                    <h6 class="m-0 font-weight-bold text-dark"><?php echo i8ln("AREAS"); ?>
                                    <!-- Button trigger modal -->
                                    <div style="text-align:left; margin-top:5px;">
                                    <button type="button" class="btn btn-success btn-circle btn-md" data-toggle="modal"
                                        data-target="#areasModal">
                                        <i class="fas fa-edit"></i>
				    </button>
			    	    </div>
                                    </h6>
				    <?php } ?>
                                    <?php if (@$disable_location <> "True") { ?>
                                    <h6 class="m-0 font-weight-bold text-dark" style="text-align:right;"><?php echo i8ln("LOCATION"); ?>
				    <!-- Button trigger modal -->
                                        <div style="text-align:right; margin-top:5px;">
                                        <a href="#" class="btn btn-danger btn-circle btn-md"
                                            data-toggle="modal"
                                            data-target="#DeleteLocationModal">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php 
			                   if( isset($_SERVER['HTTPS'] ) ) { $site_is_https = "True"; }
			                   if( isset($site_is_https) && $site_is_https == "True") { 
                                        ?>
                                        <button type="button" class="btn btn-success btn-circle btn-md"
                                            onclick="getLocation()">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                        <?php } ?>
                                        <?php if (@$disable_nominatim <> "True") { ?>
                                        <button type="button" class="btn btn-success btn-circle btn-md"
                                            data-toggle="modal" data-target="#locationModal">
                                            <i class="fas fa-edit"></i>
					</button>
                                        <?php } ?>
					</div>
                                    </h6>
                                    <?php } ?>

                                </div>
			    </div>

                            <div class="card-body">
                                <?php if (@$disable_areas <> "True") { ?>
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="row">

                                            <?php
                                            if ($area == "[]") {
                                            ?>
                                            <div class="alert alert-warning w-100 m-3" role="alert">
                                                <?php echo i8ln("You have not set any area yet!"); ?>
                                            </div>
                                            <?php
                                                $areas = "";
                                            } else {
                                                $areas = explode(",", $area);

                                                foreach ($areas as $key => $area) {
                                                    $area = str_replace('"', '', $area);
                                                    $area = str_replace('[', '', $area);
                                                    $area = str_replace(']', '', $area);
                                                ?>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 text-center">
                                                <div class="card bg-dark text-white shadow">
                                                    <div class="card-body-areas">
                                                        <?php echo strtoupper($area); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                <?php } ?>
                                <?php if (@$disable_location <> "True") { ?>
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="row">

                                            <div id="position_error_div" style="display:none;"
                                                class="text-center w-100 m-3">
                                                <div id="PERMISSION_DENIED" style="display:none;"
                                                    class="alert alert-danger" role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("User denied the request for Geolocation"); ?>.
                                                </div>
                                                <div id="POSITION_UNAVAILABLE" style="display:none;"
                                                    class="alert alert-danger" role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("Location information is unavailable"); ?>.
                                                </div>
                                                <div id="TIMEOUT" style="display:none;" class="alert alert-danger"
                                                    role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("The request to get user location timed out"); ?>.
                                                </div>
                                                <div id="UNKNOWN_ERROR" style="display:none;" class="alert alert-danger"
                                                    role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("An unknown error occurred"); ?>.
                                                </div>
                                                <div id="NOT_SUPPORTED" style="display:none;" class="alert alert-danger"
                                                    role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("Geolocation is not supported by this browser"); ?>.
                                                </div>
                                            </div>


                                            <?php
                                            if ($latitude == "0.0000000000" && $longitude == "0.0000000000") {
                                            ?>
                                                <div class="alert alert-warning w-100 m-3" role="alert">
                                                    <?php echo i8ln("Your location is not set. Distance settings won't be taken into account."); ?>
                                                </div>
                                            <?php
					                        } else {
                                                ?>
					    <div class="alert alert-success w-100 m-3" role="alert">
						<?php echo i8ln("Your Location is set to"); ?><br>
                                                <?php 
					        if (@$disable_nominatim <> "True") { 
                                                   $address=get_address($latitude, $longitude); 
						   echo "<b>".$address."</b><br>"; 
						} 
                                                ?>
                                                <?php echo "[ ".round($latitude, 4); ?>,
                                                <?php echo round($longitude, 4)." ]"; ?>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- DELETE LOCATION Modal -->
                    <div class="modal fade" id="DeleteLocationModal" tabindex="-1" role="dialog"
                        aria-labelledby="DeleteLocationLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="DeleteLocationModalTitle">
                                        <?php echo i8ln("Delete Location and Distance Settings"); ?>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
				    <?php echo i8ln("This will delete your location AND all your distance settings"); ?><br>
                                    <?php echo i8ln("Are you sure?"); ?>
                                </div>
                                <div class="modal-footer">
                                    <a href="./actions/set_location.php?action=delete"
                                        class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT AREAS Modal -->
                    <div class="modal fade" id="areasModal" tabindex="-1" role="dialog"
                        aria-labelledby="areasModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php include "./modal/areas_modal.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT LOCATION Modal -->
                    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog"
                        aria-labelledby="locationModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php include "./modal/location_modal.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Content Row -->

                <hr>

                <ul class="nav nav-pills mb-3 mx-auto justify-content-center" id="pills-tab-home" role="tablist">
                    <?php if (@$disable_mons <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-mons-tab" data-toggle="pill" href="#pills-mons" role="tab"
                            aria-controls="pills-mons" aria-selected="true"><?php echo i8ln("POKÉMON"); ?></a>
                    </li>
                    <?php
                    }
                    ?>

                    <?php if (@$disable_raids <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-raids-tab" data-toggle="pill" href="#pills-raids" role="tab"
                            aria-controls="pills-raids" aria-selected="false"><?php echo i8ln("RAIDS"); ?></a>
                    </li>
                    <?php
                    }
                    ?>

                    <?php if (@$disable_quests <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-quests-tab" data-toggle="pill" href="#pills-quests" role="tab"
                            aria-controls="pills-quests" aria-selected="false"><?php echo i8ln("QUESTS"); ?></a>
                    </li>
                    <?php
                    }
                    ?>

                    <?php if (@$disable_invasions <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-invasions-tab" data-toggle="pill" href="#pills-invasions" role="tab"
                            aria-controls="pills-invasions" aria-selected="false"><?php echo i8ln("INVASIONS"); ?></a>
                    </li>
                    <?php
                    }
                    ?>

                    <?php if (@$disable_lures <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-lures-tab" data-toggle="pill" href="#pills-lures" role="tab"
                            aria-controls="pills-lures" aria-selected="false"><?php echo i8ln("LURES"); ?></a>
                    </li>
                    <?php
                    }
                    ?>


		</ul>
                <div class="tab-content mb-5" id="pills-tab-homeContent">
                    <?php if (@$disable_mons <> "True") { ?>
                    <div class="tab-pane fade show active" id="pills-mons" role="tabpanel"
                        aria-labelledby="pills-mons-tab">

                        <hr>

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

                    <?php if (@$disable_raids <> "True") { ?>
                    <div class="tab-pane fade" id="pills-raids" role="tabpanel" aria-labelledby="pills-raids-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("EGGS & RAIDS TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_raid_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL Raids/Eggs"); ?></strong>!
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
                                <a href="pages/add/raids.php" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllRaidEggsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL"); ?></span>
				</a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceRaidsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL RAID/EGGS Modal -->
                        <div class="modal fade" id="deleteAllRaidEggsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllRaidEggsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllRaidEggsModalLabel">
                                            <?php echo i8ln("Delete ALL Raid/Eggs?"); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Eggs & Raids Alarms and cannot be undone, are you sure ?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/raids.php?action=delete_all_raids"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE RAIDS_EGGS DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceRaidsModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceRaidsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <?php include "./modal/distance_raids_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Eggs & Raids

                                $sql = "select * FROM egg WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "' ORDER BY level";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $egg_unique_id = "raid_" . $row['uid'];

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
                                                        src='<?php echo $imgUrl . "/egg" . $row['level'] . ".png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln("Eggs"); ?> <?php echo $row['level']; ?>
                                                </div>
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
                                                        <a href="#DistanceShowEggs" data-toggle="modal" data-target="#DistanceShowEggs_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowEggs_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowEggsTitle" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_raid_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if (isset($allowed_templates["eggs"])) {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["eggs"]) ? $allowed_templates["eggs"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $egg_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $egg_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT EGG Modal -->
                            <div class="modal fade" id="<?php echo $egg_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $egg_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_eggs_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE EGG Modal -->
                            <div class="modal fade" id="<?php echo $egg_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $egg_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_eggs_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }

				$sql = "select * FROM raid WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "' 
					AND pokemon_id = 9000 ORDER BY level";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $raid_unique_id = "raid_" . $row['uid'];

                                ?>

                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-warning shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
                                                        src='<?php echo "./img/raid_" . $row['level'] . ".png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln("Raids"); ?> <?php echo $row['level']; ?>
                                                </div>
                                                <div class="mt-2 text-center">
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
                                                            if ($row['clean'] == '1' && $all_raid_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>
						    <?php 
                                                            } 
                                                            if (isset($allowed_templates["raids"])) {
                                                            ?>
                                                    <div class="mt-1">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["raids"]) ? $allowed_templates["raids"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $raid_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $raid_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT Raid Modal -->
                            <div class="modal fade" id="<?php echo $raid_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $raid_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_raids_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- DELETE RAID Modal -->
                            <div class="modal fade" id="<?php echo $raid_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $raid_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_raids_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }

				$sql = "select * FROM raid WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'  
					AND pokemon_id <> 9000 ORDER BY pokemon_id";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $raid_unique_id = "raid_" . $row['uid'];
                                    $pokemon_name = get_mons($row['pokemon_id']);

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-danger shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
                                                        src='<?php echo $imgUrl . "/pokemon_icon_" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "_00.png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo $pokemon_name; ?>
                                                </div>
                                                <div class="mt-2 text-center">

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
                                                        <a href="#DistanceShowRaidMons" data-toggle="modal" data-target="#DistanceShowRaidMons_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowRaidMons_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowRaidMonsTitle" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_raid_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if (isset($allowed_templates["raids"])) {
                                                            ?>
                                                    <div class="mt-1">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["raids"]) ? $allowed_templates["raids"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $raid_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $raid_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT Monster Raid Modal -->
                            <div class="modal fade" id="<?php echo $raid_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $raid_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_raids_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- DELETE Monster RAID Modal -->
                            <?php $pokemon_name=get_mons($row['pokemon_id']); ?>
                            <div class="modal fade" id="<?php echo $raid_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $raid_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_monster_raids_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                                ?>

                        </div>

                    </div>
                    <?php
                    } // End of Raids Disable 
                    ?>

                    <?php
                    if (@$disable_quests <> "True") {
                    ?>
                    <div class="tab-pane fade" id="pills-quests" role="tabpanel" aria-labelledby="pills-quests-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("QUESTS TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_quest_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL Quests"); ?></strong>!
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
                                <a href="pages/add/quests.php" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllQuestsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL") ?></span>
				</a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceQuestsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL QUESTS Modal -->
                        <div class="modal fade" id="deleteAllQuestsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllQuestsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllQuestsModalLabel">
                                            <?php echo i8ln("Delete ALL Quests?"); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Quests Alarms and cannot be undone, are you sure ?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/quests.php?action=delete_all_quests"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE QUESTS DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceQuestsModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceQuestsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <?php include "./modal/distance_quests_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Quests

				$sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "' 
					AND reward_type = 7 ORDER BY reward";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['uid'];

                                    // Add Hidden Fancy Boxes
                                    $mon_id = str_pad($row['reward'], 3, "0", STR_PAD_LEFT);
                                    $pokemon_name = get_mons($row['reward']);

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
                                                        src='<?php echo $imgUrl . "/pokemon_icon_" . $mon_id . "_00.png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo $pokemon_name; ?>
                                                </div>
                                                <div class="mt-2 text-center">

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
                                                        <a href="#DistanceShowQuests" data-toggle="modal" data-target="#DistanceShowQuests_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowQuests_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowQuestsTitle" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_quest_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if (isset($allowed_templates["quests"])) {
                                                            ?>
						    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["quests"]) ? $allowed_templates["quests"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $quest_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $quest_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT QUEST Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $quest_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_quests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- DELETE QUEST Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $quest_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
                                        <?php $delete_title= i8ln("Delete Quests tracking for"); ?>
                                        <?php include "./modal/delete_quests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>


                            <?php
                                }

				$sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "' 
					AND reward_type = 2 ORDER BY reward";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['uid'];

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-warning shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
                                                        src='<?php echo $imgUrl . "/rewards/reward_" . $row['reward'] . "_1.png"; ?>'>
                                                </div>
                                                <div class="mt-2 text-center">

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
                                                        <a href="#DistanceShowQuests" data-toggle="modal" data-target="#DistanceShowQuests_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowQuests_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowQuestsTitle" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_quest_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if (isset($allowed_templates["quests"])) {
                                                            ?>
						    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["quests"]) ? $allowed_templates["quests"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $quest_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $quest_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT QUEST Item Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $quest_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_quests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- DELETE QUEST Item Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $quest_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
					<?php 
                                           $delete_title= i8ln("Delete Quests tracking for this item"); 
                                           $pokemon_name="";
					   include "./modal/delete_quests_modal.php"; 
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }

				$sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'  
					AND reward_type = 12 ORDER BY reward";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['uid'];

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-warning shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
							<?php if ($row['reward'] <> 0) { $pokemon_name = get_mons($row['reward']); ?>
                                                        src='<?php echo $imgUrl . "/rewards/reward_mega_energy_" . $row['reward'] . ".png"; ?>'>
							<?php } else  { $pokemon_name = i8ln("ALL"); ?>
                                                        src='<?php echo $imgUrl . "/rewards/reward_mega_energy.png"; ?>'>
							<?php } ?>
						</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo $pokemon_name; ?><br>
						    <span class='badge badge-light m-1'><?php echo i8ln("Mega Energy"); ?></span>
                                                </div>
                                                <div class="mt-2 text-center">

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
                                                        <a href="#DistanceShowQuests" data-toggle="modal" data-target="#DistanceShowQuests_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowQuests_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowQuestsTitle" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_quest_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if (isset($allowed_templates)) {
                                                            ?>
						    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["quests"]) ? $allowed_templates["quests"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $quest_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $quest_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EDIT QUEST MEGA Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $quest_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_quests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- DELETE QUEST MEGA Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $quest_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
                                        <?php
                                           $delete_title= i8ln("Delete Quests tracking for this Mega Energy");
                                           $pokemon_name="";
                                           include "./modal/delete_quests_modal.php";
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>

                    </div>
                    <?php
                    } // End of Quests Disable 
                    ?>

                    <?php if (@$disable_invasions <> "True") { ?>
                    <div class="tab-pane fade" id="pills-invasions" role="tabpanel" aria-labelledby="pills-invasions-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("INVASIONS TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_invasion_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL INVASIONS"); ?></strong>!
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
                                <a href="pages/add/invasions.php" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllInvasionsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL"); ?></span>
                                </a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceInvasionsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL INVASIONS Modal -->
                        <div class="modal fade" id="deleteAllInvasionsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllInvasionsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllInvasionsModalLabel">
                                            <?php echo i8ln("Delete ALL Invasions?"); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Invasions Alarms and cannot be undone"); ?><br>
                                        <?php echo i8ln("Are you sure?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/invasions.php?action=delete_all_invasions"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE INVASIONS DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceInvasionsModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceInvasionsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <?php include "./modal/distance_invasions_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Invasions

		                $sql = "SELECT * FROM invasion WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'  
			                ORDER BY grunt_type";
                                $result = $conn->query($sql); 

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $invasion_unique_id = "invasion_" . $row['uid'];

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <?php if ( $row['grunt_type'] == "mixed" && $row['gender'] == "0") { ?>
                                                    <img width=50 loading=lazy src='<?php echo "./grunts/James.png?"; ?>'>
                                                    <img width=50 loading=lazy src='<?php echo "./grunts/Jessie.png?"; ?>'>
                                                    <?php } else if ( $row['grunt_type'] == "mixed" && $row['gender'] == "1") { ?>
                                                    <img width=50 loading=lazy src='<?php echo "./grunts/James.png?"; ?>'>
                                                    <?php } else if ( $row['grunt_type'] == "mixed" && $row['gender'] == "2") { ?>
                                                    <img width=50 loading=lazy src='<?php echo "./grunts/Jessie.png?"; ?>'>
                                                    <?php } else if ( $row['grunt_type'] == "everything") { ?>
                                                    <div class="h5 mb-0 mt-2 font-weight-bold text-gray-800 text-center"
                                                         style="height: 70px;">
                                                         <font style='font-size:32px;'><?php echo i8ln("ALL"); ?></font>
                                                    </div>

                                                    <?php } else { ?>
                                                    <img width=50 loading=lazy src='<?php echo "./grunts/" . $row['grunt_type'] . ".png?"; ?>'>
                                                    <?php } ?>
						</div>
                                                <?php if ( $row['grunt_type'] <> "everything") { ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo ucfirst(i8ln($row['grunt_type'])); ?>
                                                </div>
                                                <?php } ?>
						<div class="mt-2 text-center">
                                                <ul class="list-group mt-2 mb-2">
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
                                                    <?php if ($row['distance'] <> '0') { ?>
                                                    <li
							class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("DISTANCE"); ?>
                                                        <?php if ( @$distance_map <> "True" ) { ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                        </span>
                                                        <?php } else { ?>
                                                        <a href="#DistanceShowInvasions" data-toggle="modal" data-target="#DistanceShowInvasions_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
							</a>
							<?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowInvasions_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowInvasions" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_invasions_cleaned == '0') {
                                                    ?>
                                                    <div class="mt-1">
                                                       <span class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>

						    <?php 
                                                            }
                                                            if (isset($allowed_templates["invasions"])) {
                                                    ?>
						    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["invasions"]) ? $allowed_templates["invasions"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $invasion_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $invasion_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT INVASION Modal -->
                            <div class="modal fade" id="<?php echo $invasion_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $invasion_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_invasions_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE INVASION Modal -->
                            <div class="modal fade" id="<?php echo $invasion_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $invasion_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_invasions_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                        </div>

                    </div>
                    <?php
                    } // End of Invasions Disable
                    ?>

                    <?php if (@$disable_lures <> "True") { ?> 
                    <div class="tab-pane fade" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("LURES TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_lures_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL LURES"); ?></strong>!
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
                                <a href="pages/add/lures.php" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllLuresModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL"); ?></span>
                                </a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceLuresModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL LURES Modal -->
                        <div class="modal fade" id="deleteAllLuresModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllLuresModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllLuresModalLabel">
                                            <?php echo i8ln("Delete ALL Lures?"); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Lures Alarms and cannot be undone"); ?><br>
                                        <?php echo i8ln("Are you sure?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/lures.php?action=delete_all_lures"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE LURES DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceLuresModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceLuresModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <?php include "./modal/distance_lures_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Lures

                                $sql = "SELECT * FROM lures WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'
                                        ORDER BY lure_id";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $lure_unique_id = "lure_" . $row['uid'];

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <?php if ( $row['lure_id'] == "0") { ?>
                                                    <div class="h5 mb-0 mt-2 font-weight-bold text-gray-800 text-center"
                                                         style="height: 70px;">
                                                         <font style='font-size:32px;'><?php echo i8ln("ALL"); ?></font>
                                                    </div>
                                                    <?php } else { ?>
                                                    <img width=50 loading=lazy src='<?php echo "./lures/" . $row['lure_id'] . ".png?"; ?>'>
                                                    <?php } ?>
						</div>

                                                <?php if ( $row['lure_id'] <> "0") { ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln(get_lure_name($row['lure_id'])); ?>
						</div>
						<?php } ?>

                                                <div class="mt-2 text-center">
                                                <ul class="list-group mt-2 mb-2">
                                                    <?php if ($row['distance'] <> '0') { ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("DISTANCE"); ?>
                                                        <?php if ( @$distance_map <> "True" ) { ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                        </span>
                                                        <?php } else { ?>
                                                        <a href="#DistanceShowLures" data-toggle="modal" data-target="#DistanceShowLures_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowLures_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowLures" aria-hidden="true">
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
                                                            if ($row['clean'] == '1' && $all_lures_cleaned == '0') {
                                                    ?>
                                                    <div class="mt-1">
                                                       <span class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>

						    <?php 
                                                            }
                                                            if (isset($allowed_templates["lures"])) {
                                                    ?>
						    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["lures"]) ? $allowed_templates["lures"][$row['template']] : 'UNKNOWN'; ?></span>
                                                    </div>
                                                    <?php } ?>
                                                </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $lure_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $lure_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT LURE Modal -->
                            <div class="modal fade" id="<?php echo $lure_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $lure_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_lures_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE LURE Modal -->
                            <div class="modal fade" id="<?php echo $lure_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $lure_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_lures_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                        </div>

                    </div>
                    <?php
                    } // End of Lures Disable
                    ?>


