<?php 
include "./header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<?php
    if ($gAnalyticsId != "") {
        echo '<!-- Google Analytics -->
            <script>
                window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
                ga("create", "' . $gAnalyticsId . '", "auto");
                ga("send", "pageview");
            </script>
            <script async src="https://www.google-analytics.com/analytics.js"></script>
            <!-- End Google Analytics -->';
    }
?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?></title>

    <link rel="icon" type="image/x-icon" href="favicon.png" />

    <!-- Custom fonts for this template-->
    <link href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/custom-bootstrap.css?v=<?=time();?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

		<?php include "topbar.php" ?>

                <!-- Begin Page Content -->
		<div class="container-fluid col-lg-8 col-md-12">

		<?php echo @$admin_alarm; ?>

                    <!-- Profile Settings Modal -->
                    <?php include "./modal/profile_settings_modal.php"; ?>

                    <!-- Success Alerts-->
                    <?php
                        if (isset($_GET['return']) && $_GET['return'] == 'success_added_mons') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Added Monster Alarm(s)"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_mons') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Updated Monster Alarm"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_mons') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Deleted Monster Alarm(s)"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_added_raids') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Added Raids Alarm(s)"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_raid') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Updated Raid Alarm"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_raid') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Deleted Raid Alarm"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_raids') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Deleted Eggs & Raids Alarm(s)"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_egg') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Updated Egg Alarm"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_egg') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Deleted Egg Alarm"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_added_quest') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Added Quest Alarm(s)"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_quest') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Updated Quest Alarm"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_quest') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Deleted Quest Alarm(s)"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_areas') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Updated List of Areas"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_settings') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Settings updated successfully"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
			}
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_location') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Location updated successfully"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
		    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_mons_distance') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Update Distance on ALL Pokémons"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_raids_distance') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Update Distance on ALL Raids & Eggs"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_quests_distance') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Update Distance on ALL Quests"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
		    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_migrate') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo i8ln("Successfully Migrated account")."<br>".$_GET['mig_source']."
                           <i class='fas fa-arrow-circle-right'></i> ".$_GET['mig_target']."!"; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
		    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_channel_sync') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Successfully Synchronized Channels"); ?> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'error_update_location') {
                        ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Address not found. Try Again"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
		    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'user_not_found') {
                        ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo i8ln("User not found. Try Again"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if ($enabled==0) {
                        ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo i8ln("Your alarms are currently disabled!"); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'sql_error') {
			    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
			    echo i8ln("You Request couldn't not be handled"); 
			    echo i8ln("Error"). " #" . $_GET['phase'];
                            if ($debug == 'True') {
                                echo "<br><br>" . $_GET['sql'];
                            }
                        ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
                        }

                ?>

                <!-- Content Row -->
                <div class="row">

                    <!-- Card -->
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
                                    <h6 class="m-0 font-weight-bold text-dark"><?php echo i8ln("LOCATION"); ?>
                                    <!-- Button trigger modal -->
                                        <?php 
			                   if( isset($_SERVER['HTTPS'] ) ) { $site_is_https = "True"; }
			                   if( isset($site_is_https) && $site_is_https == "True") { 
                                        ?>
                                        <div style="text-align:right; margin-top:5px;">
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
					</div>
                                        <?php } ?>
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
                            aria-controls="pills-mons" aria-selected="true"><?php echo i8ln("POKÉMONS"); ?></a>
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


		</ul>
                <div class="tab-content mb-5" id="pills-tab-homeContent">
                    <?php if (@$disable_mons <> "True") { ?>
                    <div class="tab-pane fade show active" id="pills-mons" role="tabpanel"
                        aria-labelledby="pills-mons-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
				<h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("POKÉMONS TRACKED"); ?></h1>
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
                                <a href="./add_mons.php" class="btn btn-success btn-icon-split mr-2 mt-1">
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
                                $sql = "select * FROM monsters WHERE id = '" . $_SESSION['id'] . "'";
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

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Check if User is already tracking something

                                $sql = "select count(*) count FROM monsters WHERE id = '" . $_SESSION['id'] . "'";
                                $result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$num_mon_tracked = $row['count'];
				}

                                // Show Monsters Alarms         

                                $sql = "select * FROM monsters WHERE id = '" . $_SESSION['id'] . "' " . @$gen_selector ." ORDER BY pokemon_id, form"; 
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

                                    $pkm_unique_id = "mon_" . $row['pokemon_id'] . "_" .
                                        $row['form'] . "_" . $row['distance'] . "_" . $row['gender'] .
                                        $row['min_cp'] . "_" . $row['max_cp'] . "_" .
                                        $row['min_iv'] . "_" . $row['max_iv'] . "_" .
                                        $row['min_level'] . "_" . $row['max_level'] . "_" .
                                        $row['min_weight'] . "_" . $row['max_weight'] . "_" .
                                        $row['atk'] . "_" . $row['def'] . "_" . $row['sta'] . "_" .
                                        $row['max_atk'] . "_" . $row['max_def'] . "_" . $row['max_sta'] . "_" .
                                        $row['great_league_ranking'] . "_" . $row['great_league_ranking_min_cp'] . "_" .
                                        $row['ultra_league_ranking'] . "_" . $row['ultra_league_ranking_min_cp'];


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
                                                        class="badge badge-primary badge-pill w-100"><?php echo $pokemon_name." ".$form_name; ?></span>
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
                                                        ?>

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
                                <a href="./add_raids.php" class="btn btn-success btn-icon-split mr-2 mt-1">
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

                                $sql = "select * FROM egg WHERE id = '" . $_SESSION['id'] . "' ORDER BY level";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $egg_unique_id = "raid_00_" .
                                        $row['distance'] .
                                        $row['team'] . "_" . $row['level'];

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
                                                            ?>
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

                                $sql = "select * FROM raid WHERE id = '" . $_SESSION['id'] . "' AND pokemon_id = 9000 ORDER BY level";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $raid_unique_id = "raid_" . $row['pokemon_id'] . "_" .
                                        $row['form'] . "_" . $row['distance'] .
                                        $row['team'] . "_" . $row['level'];

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

                                $sql = "select * FROM raid WHERE id = '" . $_SESSION['id'] . "' AND pokemon_id <> 9000 ORDER BY pokemon_id";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $raid_unique_id = "raid_" . $row['pokemon_id'] . "_" .
                                        $row['form'] . "_" . $row['distance'] .
                                        $row['team'] . "_" . $row['level'];

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
                                                            ?>
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
                                <a href="./add_quests.php" class="btn btn-success btn-icon-split mr-2 mt-1">
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

                                $sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' AND reward_type = 7 ORDER BY reward";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['reward'] . "_" .
                                        $row['reward_type'] . "_" . $row['distance'];

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
                                                            ?>
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

                                $sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' AND reward_type = 2 ORDER BY reward";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['reward'] . "_" .
                                        $row['reward_type'] . "_" . $row['distance'];

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
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?>
                                                            <?php echo i8ln("meters"); ?>
                                                        </span>
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
                                                            ?>
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
                                        <?php include "./modal/quests_modal.php"; ?>
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

                                $sql = "select * FROM quest WHERE id = '" . $_SESSION['id'] . "' AND reward_type = 12 ORDER BY reward";
                                $result = $conn->query($sql);

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $quest_unique_id = "quest_" . $row['reward'] . "_" .
                                        $row['reward_type'] . "_" . $row['distance'];

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
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?>
                                                            <?php echo i8ln("meters"); ?>
                                                        </span>
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
                                                            ?>
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
                                        <?php include "./modal/quests_modal.php"; ?>
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
                                <a href="./add_invasions.php" class="btn btn-success btn-icon-split mr-2 mt-1">
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

                                $sql = "select * FROM invasion WHERE id = '" . $_SESSION['id'] . "' ORDER BY grunt_type";
                                $result = $conn->query($sql); 

                                while ($row = $result->fetch_assoc()) {

                                    // Build a Unique Index
                                    $invasion_unique_id = "invasion_" .
                                        $row['distance'] .
                                        $row['gender'] . "_" . $row['grunt_type'];

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

                </div>

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include "./modal/logout_modal.php"; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="node_modules/jquery.easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/scripts.js"></script>
    <script type="text/javascript" src="js/get_position.js?v=<?=time();?>"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <!-- Custom scripts for Deep Linking Tabs -->
    <script type='text/javascript'>
        window.onhashchange=hashTriggerTab;
        window.onload=hashTriggerTab;

        function hashTriggerTab(){
            var current_hash=window.location.hash;
            if(current_hash.substring(0,1)=='#')current_hash=current_hash.substring(1);
            if(current_hash!=''){
                var trigger=document.querySelector('.nav-pills a[href="#'+current_hash+'"]');
                if(trigger)trigger.click();
            }
        }
    </script>

</body>

</html>
