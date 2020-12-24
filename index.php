<?php
include "./header.php";
if (isset($_SESSION['username'])) {
    // Exit if user not registered to Poracle

    $sql = "SELECT * from humans WHERE id = '" . $_SESSION['id'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {

        // Not-Registered Page
        include "./unregistered.php";

        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?></title>

    <link rel="icon" type="image/x-icon" href="favicon.png" />

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/custom-bootstrap.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar fixed-top navbar-expand navbar-dark topbar mb-4 static-top shadow"
                    style="background-color: #000000;">

                    <a class="navbar-brand" href="/"><?php echo $title; ?></a>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow" id="Dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="modal"
                                data-target="#profileSettingsModal">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?></span>
                                <img class="img-profile rounded-circle" src="<?php echo $avatar ?>">
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - Logout -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-fw"></i>
                            </a>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid col-lg-8 col-md-12">

                    <!-- Profile Settings Modal -->
                    <?php include "./modal/profile_settings_modal.php"; ?>


                    <!-- Success Alerts-->
                    <?php
                        if (isset($_GET['return']) && $_GET['return'] == 'success_added_mons') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Added Monster Alarm(s)
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_mons') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Updated Monster Alarm
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_mons') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Deleted Monster Alarm(s)
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_added_raids') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Added Raids Alarm(s)
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_raid') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Updated Raid Alarm
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_raid') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Deleted Raid Alarm
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_raids') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Deleted Eggs & Raids Alarm(s)
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_egg') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Updated Egg Alarm
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_egg') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Deleted Egg Alarm
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_added_quest') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Added Quest Alarm(s)
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_quest') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Updated Quest Alarm
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_delete_quest') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Deleted Quest Alarm(s)
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_areas') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Successfully Updated List of Areas
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'success_update_settings') {
                        ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Settings updated successfully
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                        }
                        if (isset($_GET['return']) && $_GET['return'] == 'sql_error') {
                            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>You Request couldn't not be handled. Error #" . $_GET['phase'];
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
                    <div class="col-xl-12 col-md-12">
                        <!-- Areas -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row d-flex justify-content-between align-items-center pl-3 pr-3">

                                    <h6 class="m-0 font-weight-bold text-dark">AREAS</h6>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-success btn-circle btn-md" data-toggle="modal"
                                        data-target="#areasModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="row">

                                            <?php
                                            if ($area == "[]") {
                                            ?>
                                            <div class="alert alert-warning w-100 m-3" role="alert">
                                                You have <strong>not</strong> set any area yet!
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

                </div>
                <!-- Content Row -->

                <hr>

                <ul class="nav nav-pills mb-3 mx-auto justify-content-center" id="pills-tab-home" role="tablist">
                    <?php if ($disable_mons <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-mons-tab" data-toggle="pill" href="#pills-mons" role="tab"
                            aria-controls="pills-mons" aria-selected="true">POKÉMONS</a>
                    </li>
                    <?php
                    }
                    ?>

                    <?php if ($disable_raids <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-raids-tab" data-toggle="pill" href="#pills-raids" role="tab"
                            aria-controls="pills-raids" aria-selected="false">RAIDS</a>
                    </li>
                    <?php
                    }
                    ?>

                    <?php if ($disable_quests <> "True") { ?>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-quests-tab" data-toggle="pill" href="#pills-quests" role="tab"
                            aria-controls="pills-quests" aria-selected="false">QUESTS</a>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
                <div class="tab-content mb-5" id="pills-tab-homeContent">
                    <?php if ($disable_mons <> "True") { ?>
                    <div class="tab-pane fade show active" id="pills-mons" role="tabpanel"
                        aria-labelledby="pills-mons-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb">
                                <h1 class="h3 mb-0 text-gray-800 ">POKÉMONS TRACKED</h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_mon_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    Cleaning activated on <strong>ALL Monsters</strong>!
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
                                <a href="./add_mons.php" class="btn btn-success btn-icon-split mr-2">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">ADD</span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal"
                                    data-target="#deleteAllMonsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">DELETE ALL</span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL MONS Modal -->
                        <div class="modal fade" id="deleteAllMonsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllMonsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllMonsModalTitle">Delete ALL tracked Mons?
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        This will delete all your Pokémon Alarms and cannot be undone, are you sure ?
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./form_action.php?action=delete_all_mons"
                                            class="btn btn-danger">DELETE</a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">CANCEL</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <?php

                                // Show Monsters Alarms         

                                $sql = "select * FROM monsters WHERE id = '" . $_SESSION['id'] . "' ORDER BY pokemon_id, form";
                                $result = $conn->query($sql);

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
                                            $pokemon_name = get_mons($row['pokemon_id']);
                                            $PkmnImg_50 = "<font size=5><strong>" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "</strong></font><br>$pokemon_name";
                                            $PkmnImg_100 = "<font size=8><strong>" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "</strong></font><br>$pokemon_name";
                                        } else {
                                            $PkmnImg_50 = "<img width=50 src='$PkmnImg'>";
                                            $PkmnImg_100 = "<img width=100 src='$PkmnImg'>";
                                        }
                                    } else {
                                        $PkmnImg_50 = "<img width=50 src='$PkmnImg'>";
                                        $PkmnImg_100 = "<img width=100 src='$PkmnImg'>";
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
                                                    style="height: 43px;">
                                                    ALL
                                                </div>
                                                <?php
                                                        } else {
                                                        ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <?php echo $PkmnImg_50; ?></div>
                                                <?php
                                                        }
                                                        ?>
                                                <ul class="list-group mt-2">
                                                    <?php

                                                            if ($row['distance'] <> '0') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        DISTANCE
                                                        <span
                                                            class="badge badge-primary badge-pill"><?php echo $row['distance']; ?></span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['min_iv'] <> '-1' || $row['max_iv'] <> '100') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        IV
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
                                                        IV
                                                        <span class="badge badge-primary badge-pill">ALL</span>
                                                    </li>
                                                    <?php
                                                            }
                                                            if ($row['min_cp'] <> '0' || $row['max_cp'] <> '9000') {
                                                            ?>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        CP
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
                                                        LEVEL
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
                                                        STATS
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
                                                        GREAT
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
                                                        ULTRA
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
                                                        WEIGHT
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
                                                </ul>
                                                <?php

                                                        if ($row['form'] <> '0') {
                                                            $form_name = get_form_name($row['pokemon_id'], $row['form']);
                                                        ?>
                                                <div>
                                                    <span
                                                        class="badge badge-primary badge-pill w-100"><?php echo $form_name; ?></span>
                                                </div>
                                                <?php
                                                        }
                                                        if ($row['gender'] == '1') {
                                                        ?>
                                                <div>
                                                    <span
                                                        class="badge badge-primary badge-pill w-100"><?php echo "Male"; ?></span>
                                                </div>
                                                <?php
                                                        }
                                                        if ($row['gender'] == '2') {
                                                        ?>
                                                <div>
                                                    <span
                                                        class="badge badge-primary badge-pill w-100"><?php echo "Female"; ?></span>
                                                </div>
                                                <?php
                                                        }
                                                        if ($row['clean'] == '1' && $all_mon_cleaned == '0') {
                                                        ?>
                                                <div class="mt-1">
                                                    <span class="badge badge-pill badge-info w-100">Cleaning
                                                        Activated</span>
                                                </div>
                                                <?php
                                                        }
                                                        ?>

                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center mt-2">
                                            <div class="row">
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
                                        <?php include "./modal/pokemons_modal.php"; ?>
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

                    <?php if ($disable_raids <> "True") { ?>
                    <div class="tab-pane fade" id="pills-raids" role="tabpanel" aria-labelledby="pills-raids-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb">
                                <h1 class="h3 mb-0 text-gray-800 ">EGGS & RAIDS TRACKED</h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_raid_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    Cleaning activated on <strong>ALL Raids/Eggs</strong>!
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
                                <a href="./add_raids.php" class="btn btn-success btn-icon-split mr-2">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">ADD</span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal"
                                    data-target="#deleteAllRaidEggsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">DELETE ALL</span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL RAID/EGGS Modal -->
                        <div class="modal fade" id="deleteAllRaidEggsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllRaidEggsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllRaidEggsModalLabel">Delete ALL Raid/Eggs?
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        This will delete all your Eggs & Raids Alarms and cannot be undone, are you sure
                                        ?
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./form_action.php?action=delete_all_raids"
                                            class="btn btn-danger">DELETE</a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">CANCEL</button>
                                    </div>
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
                                    $egg_unique_id = "raid_" . $row['pokemon_id'] . "_" .
                                        $row['distance'] .
                                        $row['team'] . "_" . $row['level'];

                                ?>
                            <!-- Card -->
                            <div class="col-xl-4 col-md-5 col-sm-6 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50
                                                        src='<?php echo $imgUrl . "/egg" . $row['level'] . ".png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    Eggs level <?php echo $row['level']; ?>
                                                </div>
                                                <div class="mt-2 text-center">

                                                    <?php
                                                            if ($row['distance'] <> '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?> meters
                                                        </span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if ($row['clean'] == '1' && $all_raid_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Cleaning
                                                            Activated</span>
                                                    </div>
                                                    <?php
                                                            }
                                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
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
                                        <?php include "./modal/eggs_modal.php"; ?>
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
                            <div class="col-xl-4 col-md-5 col-sm-6 col-6 mb-4">
                                <div class="card border-top-warning shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50
                                                        src='<?php echo $imgUrl . "/egg" . $row['level'] . ".png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    Raids level <?php echo $row['level']; ?>
                                                </div>
                                                <div class="mt-2 text-center">

                                                    <?php
                                                            if ($row['distance'] <> '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?> meters
                                                        </span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if ($row['clean'] == '1' && $all_raid_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Cleaning
                                                            Activated</span>
                                                    </div>
                                                    <?php
                                                            }
                                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
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
                                        <?php include "./modal/raids_modal.php"; ?>
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
                            <div class="col-xl-4 col-md-5 col-sm-6 col-6 mb-4">
                                <div class="card border-top-danger shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">
                                                    <img width=50
                                                        src='<?php echo $imgUrl . "/pokemon_icon_" . str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT) . "_00.png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo $pokemon_name; ?>
                                                </div>
                                                <div class="mt-2 text-center">

                                                    <?php
                                                            if ($row['distance'] <> '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?> meters
                                                        </span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if ($row['clean'] == '1' && $all_raid_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Cleaning
                                                            Activated</span>
                                                    </div>
                                                    <?php
                                                            }
                                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
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
                                        <?php include "./modal/raids_modal.php"; ?>
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
                    if ($disable_quests <> "True") {
                    ?>
                    <div class="tab-pane fade" id="pills-quests" role="tabpanel" aria-labelledby="pills-quests-tab">

                        <hr>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb">
                                <h1 class="h3 mb-0 text-gray-800 ">QUESTS TRACKED</h1>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                if ($all_quest_cleaned == '1') {
                                ?>
                            <div class="col">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    Cleaning activated on <strong>ALL Quests</strong>!
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
                                <a href="./add_quests.php" class="btn btn-success btn-icon-split mr-2">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">ADD</span>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal"
                                    data-target="#deleteAllQuestsModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">DELETE ALL</span>
                                </a>
                            </div>
                        </div>

                        <!-- DELETE ALL QUESTS Modal -->
                        <div class="modal fade" id="deleteAllQuestsModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteAllQuestsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllQuestsModalLabel">Delete ALL Quests?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        This will delete all your Quests Alarms and cannot be undone, are you sure ?
                                    </div>
                                    <div class="modal-footer">
                                        <a href="./form_action.php?action=delete_all_quests"
                                            class="btn btn-danger">DELETE</a>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">CANCEL</button>
                                    </div>
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
                                                    <img width=50
                                                        src='<?php echo $imgUrl . "/pokemon_icon_" . $mon_id . "_00.png"; ?>'>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo $pokemon_name; ?>
                                                </div>
                                                <div class="mt-2 text-center">

                                                    <?php
                                                            if ($row['distance'] <> '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?> meters
                                                        </span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if ($row['clean'] == '1' && $all_quest_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Cleaning
                                                            Activated</span>
                                                    </div>
                                                    <?php
                                                            }
                                                            ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
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
                                        <?php include "./modal/quests_modal.php"; ?>
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
                                                    <img width=50
                                                        src='<?php echo $imgUrl . "/rewards/reward_" . $row['reward'] . "_1.png"; ?>'>
                                                </div>
                                                <div class="mt-2 text-center">

                                                    <?php
                                                            if ($row['distance'] <> '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-primary p-2">
                                                            <?php echo $row['distance']; ?> meters
                                                        </span>
                                                    </div>
                                                    <?php
                                                            }
                                                            if ($row['clean'] == '1' && $all_quest_cleaned == '0') {
                                                            ?>
                                                    <div class="mb-2">
                                                        <span class="badge badge-pill badge-info w-100">Cleaning
                                                            Activated</span>
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

                            <!-- EDIT Raid Modal -->
                            <div class="modal fade" id="<?php echo $quest_unique_id ?>Modal" tabindex="-1" role="dialog"
                                aria-labelledby="<?php echo $quest_unique_id ?>ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <?php include "./modal/quests_modal.php"; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                                ?>

                        </div>

                    </div>
                    <?php
                    } // End of Quests Disable 
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

</body>

</html>

<?php
    // If not logged in import login page
} else {

    // Login Page
    include "./login.php";

    exit();
}

?>