
                    <?php if (@$disable_nests <> "True") { ?> 
                    <div class="tab-pane fade active show" id="pills-nests" role="tabpanel" aria-labelledby="pills-nests-tab">

                        <!-- Top Quick Links  -->
                        <?php include "include/toplinks.php"; ?>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("NESTS TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_nests_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL NESTS"); ?></strong>!
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
                                <a href="./?type=add&page=nest" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllNestsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL"); ?></span>
                                </a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceNestsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL NESTS Modal -->
                        <div class="modal fade" id="deleteAllNestsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllNestsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllNestsModalLabel">
                                            <?php echo i8ln("Delete ALL Nests?"); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Nests Alarms and cannot be undone"); ?><br>
                                        <?php echo i8ln("Are you sure?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/nests.php?action=delete_all_nests"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE NESTS DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceNestsModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceNestsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <?php include "./modal/distance_nests_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Nests

                                $sql = "SELECT * FROM nests WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'
                                        ORDER BY pokemon_id";
				$result = $conn->query($sql);

                                if ($result->num_rows == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
                                }

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $nest_unique_id = "nest_" . $row['uid'];

                                ?>
			    <!-- Card -->

                            <?php 
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

                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <?php if ( $row['pokemon_id'] == "0") { ?>
                                                    <div class="h5 mb-0 mt-2 font-weight-bold text-gray-800 text-center"
                                                         style="height: 70px;">
                                                         <font style='font-size:32px;'><?php echo i8ln("ALL"); ?></font>
                                                    </div>
						    <?php } else { ?>
                                                    <?php echo $PkmnImg_50; ?>
                                                    <?php } ?>
						</div>

                                                <?php if ( $row['pokemon_id'] <> "0") { ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln(get_mons($row['pokemon_id'])); ?>
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
                                                        <a href="#DistanceShowNests" data-toggle="modal" data-target="#DistanceShowNests_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
						    </li>

                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowNests_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowNests" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <?php include "./modal/distance_show_modal.php"; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <?php }
                                                    if ($row['min_spawn_avg'] <> '0') { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo i8ln("MIN SPAWNS"); ?>
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['min_spawn_avg']; ?>
                                                        </span>
                                                    </li>

                                                    <?php
                                                    }
                                                            if ($row['clean'] == '1' && $all_nests_cleaned == '0') {
                                                    ?>
                                                    <div class="mt-1">
                                                       <span class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>

						    <?php 
                                                            }
                                                            if (isset($allowed_templates["nests"])) {
                                                    ?>
						    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Template: <?php echo array_key_exists($row['template'], $allowed_templates["nests"]) ? $allowed_templates["nests"][$row['template']] : 'UNKNOWN'; ?></span>
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
                                                    data-target="#<?php echo $nest_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $nest_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT NEST Modal -->
                            <div class="modal fade" id="<?php echo $nest_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $nest_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_nests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE NEST Modal -->
                            <div class="modal fade" id="<?php echo $nest_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $nest_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_nests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                        </div>

                    </div>
                    <?php
                    } // End of Nests Disable
                    ?>


