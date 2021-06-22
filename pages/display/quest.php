
                    <?php
                    if (@$disable_quests <> "True") {
                    ?>
                    <div class="tab-pane fade active show" id="pills-quests" role="tabpanel" aria-labelledby="pills-quests-tab">

                        <!-- Top Quick Links  -->
                        <?php include "include/toplinks.php"; ?>

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
                                <a href="./?type=add&page=quest" class="btn btn-success btn-icon-split mr-2 mt-1">
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

				if ($result->num_rows == 0) {
                                   echo "<div class='alert alert-warning w-100 m-3' role='alert'>";
                                   echo i8ln("You have not set any Alarm yet!");
                                   echo "</div>";
				}

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['uid'];

                                    // Add Hidden Fancy Boxes
                                    $mon_id = str_pad($row['reward'], 3, "0", STR_PAD_LEFT);
				    $pokemon_name = get_mons($row['reward']);
				    $form=get_form_name($row['reward'],$row['form']);
				    if ( $form == "Normal" ) { $form = ""; }
                                ?>
                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50 loading=lazy
                                                        src='<?php echo $imgUrl."/pokemon_icon_".$mon_id."_".str_pad($row['form'], 2, "0", STR_PAD_LEFT).".png"; ?>'>
                                                </div>
                                                <div class="mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                   <span class="badge badge-primary badge-pill w-100"><?php echo $row['reward']." | ".$pokemon_name." ".$form; ?></span>
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

                                                    <?php if ( $row['distance'] > 0 ) { ?>
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
                                                    <?php } ?>

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

                                                    <?php if ( $row['distance'] > 0 ) { ?>
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
                                                    <?php } ?>

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
						<div class="mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <span class="badge badge-primary badge-pill w-100"><?php echo $row['reward']." | ".$pokemon_name; ?></span>
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

                                                    <?php if ( $row['distance'] > 0 ) { ?>
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
                                                    <?php } ?>

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
                            <?php } 


				$sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' and profile_no = '" . $_SESSION['profile'] . "'  
					AND reward_type = 4 ORDER BY reward";
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
							<?php if ($row['reward'] <> 0) { $pokemon_name = get_mons($row['reward']); ?>
							   <img width=50 loading=lazy src='./img/candy/<?php echo $row['reward']; ?>.png'>
                                                           <img width=50 loading=lazy src='<?php echo $imgUrl."/pokemon_icon_".$row['reward']."_00.png"; ?>'>
							<?php } else  { $pokemon_name = i8ln("ALL"); ?>
                                                           <img width=50 loading=lazy src='./img/candy/0.png'>
							<?php } ?> 
						</div>
						<div class="mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <span class="badge badge-primary badge-pill w-100"><?php echo $row['reward']." | ".$pokemon_name; ?></span>
						    <span class='badge badge-light m-1'><?php echo i8ln("Candy"); ?></span>
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

                                                    <?php if ( $row['distance'] > 0 ) { ?>
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
                                                    <?php } ?>

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

