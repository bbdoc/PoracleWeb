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

    <title><?php echo $title; ?> - Add Mons</title>

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
    <script type="text/javascript" src="search_mons.js"></script>

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

    $(function() {
        $("#mon_0").click(function() {
            if ($(this).is(":checked")) {
                $("#dvSearchBox").hide();
                $("#dvMonsList").hide();
                $("#dvAlertTypeAll").hide();
            } else {
                $("#dvSearchBox").show();
                $("#dvMonsList").show();
                $("#dvAlertTypeAll").show();
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
                                <strong>NEW MONSTER ALARM</strong>
                            </div>
                        </div>
                    </div>

                    <?php
                        $clean_0_checked = 0;
                        $clean_1_checked = 0;
                        if ($all_mon_cleaned == "1") {
                            $clean_1_checked = 'checked';
                        } else {
                            $clean_0_checked = 'checked';
                        }

                        ?>

                    <form action='./form_action.php' method='POST'>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Distance</div>
                                    </div>
                                    <input type="number" id='distance' name='distance' value='0' min='0'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;&nbsp;&nbsp;&nbsp;IV&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <input type='number' id='min_iv' name='min_iv' size=1 value='-1' min='-1' max='100'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">MIN</div>
                                    </div>
                                    <input type='number' id='max_iv' name='max_iv' size=1 value='100' min='-1' max='100'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">MAX</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;&nbsp;&nbsp;CP&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <input type='number' id='min_cp' name='min_cp' size=1 value='0' min='0' max='9000'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">MIN</div>
                                    </div>
                                    <input type='number' id='max_cp' name='max_cp' size=1 value='9000' min='0'
                                        max='9000' class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">MAX</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&nbsp;&nbsp;&nbsp;LVL&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <input type='number' id='min_level' name='min_level' size=1 value='0' min='0'
                                        max='50' class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">MIN</div>
                                    </div>
                                    <input type='number' id='max_level' name='max_level' size=1 value='40' min='0'
                                        max='50' class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">MAX</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Weight</div>
                                    </div>
                                    <input type='number' id='min_weight' name='min_weight' size=2 value='0' min='0'
                                        max='9000000' class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">MIN</div>
                                    </div>
                                    <input type='number' id='max_weight' name='max_weight' size=4 value='9000000'
                                        min='0' max='9000000' class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">MAX</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <label>MIN STATS</label>
                                <div class="input-group">
                                    <input type='number' id='atk' name='atk' size=1 value='0' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">ATK</div>
                                    </div>
                                    <input type='number' id='def' name='def' size=1 value='0' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">DEF</span>
                                    </div>
                                    <input type='number' id='sta' name='sta' size=1 value='0' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">STA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <label>MAX STATS</label>
                                <div class="input-group">
                                    <input type='number' id='max_atk' name='max_atk' size=1 value='15' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">ATK</div>
                                    </div>
                                    <input type='number' id='max_def' name='max_def' size=1 value='15' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">DEF</span>
                                    </div>
                                    <input type='number' id='max_sta' name='max_sta' size=1 value='15' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
                                        <span class="input-group-text">STA</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <label>PvP Great</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">MIN Rank</div>
                                    </div>
                                    <input type='number' id='great_league_ranking' name='great_league_ranking' size=1
                                        value='4096' min='0' max='4096' class="form-control text-center">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">MIN CP</span>
                                    </div>
                                    <input type='number' id='great_league_ranking_min_cp'
                                        name='great_league_ranking_min_cp' size=1 value='0' min='0' max='4096'
                                        class="form-control text-center">
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <label>PvP Ultra</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">MIN Rank</div>
                                    </div>
                                    <input type='number' id='ultra_league_ranking' name='ultra_league_ranking' size=1
                                        value='4096' min='0' max='4096' class="form-control text-center">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">MIN CP</span>
                                    </div>
                                    <input type='number' id='ultra_league_ranking_min_cp'
                                        name='ultra_league_ranking_min_cp' size=1 value='0' min='0' max='4096'
                                        class="form-control text-center">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Gender</div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="gender" id="gender_0" value="gender_0" checked>All
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="gender" id="gender_1" value="gender_1">Male
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="gender" id="gender_2" value="gender_2">Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Clean</div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_0" value="clean_0"
                                            <?php echo $clean_0_checked; ?>>
                                        No
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_1" value="clean_1"
                                            <?php echo $clean_1_checked; ?>>
                                        Yes
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class='searchmons text-center'>
                            <ul>
                                <li><input type='checkbox' name='mon_0' id='mon_0' />
                                    <label for='mon_0' style='padding:15px;' class='text-uppercase'>
                                        Apply to all Pokémons
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert" id='dvAlertTypeAll'>
                            Type <strong>ALL</strong> to display all Pokémons
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- Add Search Box -->
                        <div class='mb-3' id='dvSearchBox'>
                            <input type='text' class='form-control form-control-lg' id='search' placeholder='Search'>
                        </div>

                        <div class='searchmons text-center' id='dvMonsList'>
                            <ul>
                                <!-- Add Empty Div to be used by Ajax to display results -->
                                <div id='display'></div>

                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
                            <input class="btn btn-primary" type='submit' name='add_mon' value='Submit'>
                            <a href='<?php echo $redirect_url ?>'>
                                <button type="button" class="btn btn-secondary">Cancel</button>
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

