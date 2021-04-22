
                    <?php if (@$disable_lures <> "True") { ?> 
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Top Quick Links  -->
                        <?php include "include/toplinks.php"; ?>

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
                                <a href="./?type=add&page=lure" class="btn btn-success btn-icon-split mr-2 mt-1">
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

                                if ($result->num_rows == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
                                }

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


