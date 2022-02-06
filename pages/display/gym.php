
                    <?php if (@$disable_gyms <> "True") { ?> 
                    <div class="tab-pane fade active show" id="pills-gyms" role="tabpanel" aria-labelledby="pills-gyms-tab">

                        <!-- Top Quick Links  -->
                        <?php include "include/toplinks.php"; ?>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("GYMS TRACKED"); ?></h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_gyms_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <?php echo i8ln("Cleaning activated on"); ?>
                                    <strong><?php echo i8ln("ALL GYMS"); ?></strong>!
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
                                <a href="./?type=add&page=gym" class="btn btn-success btn-icon-split mr-2 mt-1">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("ADD"); ?></span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#deleteAllGymsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("DELETE ALL"); ?></span>
                                </a>
                                <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1" data-toggle="modal"
                                    data-target="#DistanceGymsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-crosshairs"></i>
                                    </span>
                                    <span class="text"><?php echo i8ln("UPDATE DISTANCE"); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL GYMS Modal -->
                        <div class="modal fade" id="deleteAllGymsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllGymsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllGymsModalLabel">
                                            <?php echo i8ln("Delete ALL Gyms?"); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo i8ln("This will delete all your Gyms Alarms and cannot be undone"); ?><br>
                                        <?php echo i8ln("Are you sure?"); ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./actions/gyms.php?action=delete_all_gyms"
                                            class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- UPDATE GYMS DISTANCE MODAL -->
                        <div class="modal fade" id="DistanceGymsModal" tabindex="-1" role="dialog"
                            aria-labelledby="DistanceGymsModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                        <?php include "./modal/distance_gyms_modal.php"; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Gyms

                                $sql = "SELECT * FROM gym WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'
                                        ORDER BY team"; 
				$result = $conn->query($sql);

                                if ($result->num_rows == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
                                }

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $gym_unique_id = "gym_" . $row['uid'];

                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-<?php echo get_gym_name($row['team']);?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <?php if ( $row['gym_id'] == "0") { ?>
                                                    <div class="h5 mb-0 mt-2 font-weight-bold text-gray-800 text-center"
                                                         style="height: 70px;">
                                                         <font style='font-size:32px;'><?php echo i8ln("ALL"); ?></font>
                                                    </div>
                                                    <?php } else { ?>
						    <img width=50 loading=lazy src='<?php echo "$uicons_gym/gym/" . $row['team'] . ".png?"; ?>'>
                                                    <?php } ?>
						</div>

                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln(get_gym_name($row['team']));?>
						</div>

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
                                                        <a href="#DistanceShowGyms" data-toggle="modal" data-target="#DistanceShowGyms_<?php echo $row['distance']; ?>">
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?>
                                                            <i class="fas fa-map-marked-alt"></i>
                                                        </span>
                                                        </a>
                                                        <?php } ?>
                                                    </li>

                                                    <?php if ( $row['distance'] > 0 ) { ?>
                                                    <!-- SHOW DISTANCE Modal -->
                                                    <div class="modal fade" id="DistanceShowGyms_<?php echo $row['distance']; ?>" tabindex="-1" role="dialog"
                                                        aria-labelledby="DistanceShowGyms" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <?php include "./modal/distance_show_modal.php"; ?>
                                                                </div>
                                                            </div>
                                                        </div>
						    </div>
                                                    <?php } ?>
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
                                                    <?php }
                                                            if ($row['clean'] == '1' && $all_gyms_cleaned == '0') {
                                                    ?>
                                                    <div class="mt-1">
                                                       <span class="badge badge-pill badge-info w-100"><?php echo i8ln("Cleaning Activated"); ?></span>
                                                    </div>

						    <?php 
                                                            }
                                                    if ($enable_templates == "True") {
                                                    ?>
						    <div class="mt-1">

                                                    <?php

                                                    $type = explode(":", $_SESSION['type'], 2);
                                                    if ( $type[0] == "webhook" ) { $type[0] = "discord"; }
						    $templates_locale = @$_SESSION['templates'][$type[0]]['gym'][$_SESSION['locale']];
                                                    $templates_undefined = @$_SESSION['templates'][$type[0]]['gym']['%'];
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
                                                </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <a href="#" class="btn btn-danger btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $gym_unique_id ?>DeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="#" class="btn btn-success btn-circle btn-md m-1"
                                                    data-toggle="modal"
                                                    data-target="#<?php echo $gym_unique_id ?>Modal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT GYM Modal -->
                            <div class="modal fade" id="<?php echo $gym_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $gym_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/edit_gyms_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DELETE GYM Modal -->
                            <div class="modal fade" id="<?php echo $gym_unique_id ?>DeleteModal" tabindex="-1"
                                role="dialog" aria-labelledby="<?php echo $gym_unique_id ?>DeleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/delete_gyms_modal.php"; ?>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                        </div>

                    </div>
                    <?php
                    } // End of Gyms Disable
                    ?>


