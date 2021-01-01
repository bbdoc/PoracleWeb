<?php
include "./header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?> - Add Raids</title>

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

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $("input[type='checkbox']").change(function() {
            var maxAllowed = 100;
            var cnt = $("input[type='checkbox']:checked").length;
            if (cnt > maxAllowed) {
                $(this).prop("checked", "");
                alert('Sorry, you cannot select more than ' + maxAllowed + ' Pokemons at a time!');
            }
        });
    });

    $(document).ready(function() {
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
    </script>

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

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW RAID ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <form action='./form_action.php' method='POST'>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text"><?php echo i8ln("Distance"); ?></div>
                                    </div>
                                    <input type="number" id='distance' name='distance' value='0' min='0'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("meters"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">

                                    <?php

                                        if ($row['clean'] == 0) {
                                            $checked0 = 'checked';
                                        } else {
                                            $checked0 = '';
                                        }
                                        if ($row['clean'] == 1) {
                                            $checked1 = 'checked';
                                        } else {
                                            $checked1 = '';
                                        }
                                        $clean_0_checked = 0;
                                        $clean_1_checked = 0;
                                        if ($all_raid_cleaned == "1") {
                                            $clean_1_checked = 'checked';
                                        } else {
                                            $clean_0_checked = 'checked';
                                        }

                                        ?>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
					    <div class="input-group-text"><?php echo i8ln("Clean"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_0" value="clean_0"
                                            <?php echo $clean_0_checked; ?>>
					<?php echo i8ln("No"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_1" value="clean_1"
                                            <?php echo $clean_1_checked; ?>>
					<?php echo i8ln("Yes"); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page"><?php echo i8ln("Select Egg Levels you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $eggs = explode(',', "1,3,5,6");
                                    foreach ($eggs as &$egg) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='egg_<?php echo $egg; ?>'
                                        id='egg_<?php echo $egg; ?>' />
                                    <label for='egg_<?php echo $egg; ?>'>
                                        <img src='<?php echo $imgUrl; ?>/egg<?php echo $egg; ?>.png' />
					<br><?php echo i8ln("Eggs"); ?><br><?php echo i8ln("Level"); ?> <?php echo $egg; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page"><?php echo i8ln("Select Raid Levels you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $raids = explode(',', "1,3,5,6");
                                    foreach ($raids as &$raid) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='raid_<?php echo $raid; ?>'
                                        id='raid_<?php echo $raid; ?>' />
                                    <label for='raid_<?php echo $raid; ?>'>
                                        <img src='<?php echo $imgUrl; ?>/egg<?php echo $raid; ?>.png' />
					<br><?php echo i8ln("Raids"); ?><br><?php echo i8ln("Level"); ?> <?php echo $raid; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
				<li class="breadcrumb-item active" aria-current="page"><?php echo i8ln("Select the Raid Bosses you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    #$bosses = explode(',', $raid_bosses);
                                    #foreach ($bosses as &$boss) {
                                    $bosses = get_raid_bosses();
                                    foreach ($bosses as $key => $boss) {
                                        $arr = explode("_", $boss);
                                        $boss_id = $arr[0];
                                        $boss_form = $arr[1];
                                        $boss_mega = $arr[2];
                                        if ($boss_mega == 2) {
                                            $mega_name = "Mega X";
                                        } else if ($boss_mega == 3) {
                                            $mega_name = "Mega Y";
                                        } else {
                                            $mega_name = "";
                                        }
                                        $pokemon_name = get_mons($boss_id);
                                    ?>

                                <li class='text-center'><input type='checkbox'
                                        name='mon_<?php echo $boss_id; ?>_<?php echo $boss_form; ?>'
                                        id='mon_<?php echo $boss_id; ?>_<?php echo $boss_form; ?>' />
				    <label for='mon_<?php echo $boss_id; ?>_<?php echo $boss_form; ?>'>
                                        <?php 
					   $img=$imgUrl."/pokemon_icon_".$boss.".png";
					   if (false === @file_get_contents("$img", 0, null, 0, 1)) { 
					      $img=$imgUrl."/pokemon_icon_".$boss_id."_00.png";
					   }
                                        ?>
                                        <img src='<?php echo $img; ?>' />
                                        <br>
                                        <?php echo str_pad($boss_id, 3, "0", STR_PAD_LEFT); ?>
                                        <br>
                                        <?php echo $pokemon_name; ?>
                                        <br>
                                        <?php echo $mega_name; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_raid' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
				<button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>

                    </form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Logout Modal-->
    <?php include "./modal/logout_modal.php"; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="node_modules/jquery.easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

</body>

</html>

