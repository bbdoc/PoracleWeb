<?php
include "./header.php";
if (!isset($_SESSION['admin_id'])) { 
	header("Location: $redirect_url"); 
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

    <title><?php echo $title; ?> - Admin Tools</title>

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

                    <!-- Profile Settings Modal -->
                    <?php include "./modal/profile_settings_modal.php"; ?>


                    <!-- Title -->

                    <h4 class="modal-title m-2">
                        <center><?php echo i8ln("User & Channel Management"); ?></center>
                    </h4>
                    <hr>

                    <!-- BACK TO OWN ACCOUNT -->

                    <?php  if ( isset($_SESSION['admin_id']) && $_SESSION['admin_id'] <> $_SESSION['id']) { ?>
                    <center>
                        <a href="admin_connect.php?id=<?php echo $_SESSION['admin_id']; ?>">
                            <button type="button" class="btn btn-success" style="width:300px;">
                                <?php echo i8ln("Back to own Account"); ?>
                            </button>
                        </a>
                    </center>
                    <hr>
                    <?php } ?>

                    <!-- User Access -->

                    <form action='./admin_connect.php' method='POST' class="row g-2 justify-content-center">
                        <div class="col-auto justify-center mb-1 mt-2">
                            <input type='text' autocomplete='off' class='form-control' id='id' name='id'
                                placeholder='<?php echo i8ln("Discord or Telegram ID") ?>'>
                        </div>
                        <div class="col-auto justify-center mb-1 mt-2">
                            <input class="btn btn-primary" type='submit' name='update'
                                value='<?php echo i8ln("Go"); ?>'>
                        </div>
                    </form>

                    <!-- Discord Users List -->

                    <?php

                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                       $sql = "select id, name, type FROM humans WHERE type like 'discord:user' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <strong>USERS LIST</strong>
                            </div>
                        </div>
                    </div>

                    <div class='text-uppercase text-center'>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                        <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-primary btn-icon-split m-1">
                            <span class="icon text-white-50">
                                <i class="fab fa-discord"></i>
                            </span>
                            <span class="text" style="min-width: 200px;"><?php echo $row['name']; ?></span>
                        </a>

                        <?php } ?>
                    </div>

                    <?php } ?>

                    <?php } ?>


                    <!-- Discord Channels -->

                    <?php

                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                       $sql = "select id, name, type FROM humans WHERE type like 'discord:channel' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <strong>DISCORD CHANNELS LIST</strong>
                            </div>
                        </div>
                    </div>

                    <div id='discord' class='areasform text-uppercase text-center'>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                        <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-primary btn-icon-split mr-2 mt-1">
                            <span class="icon text-white-50">
                                <i class="fab fa-discord"></i>
                            </span>
                            <span class="text" style="width:250px;"><?php echo $row['name']; ?></span>
                        </a>

                        <?php } ?>
                    </div>

                    <?php } ?>

                    <?php } ?>


                    <!--  Telegram Channels -->

                    <?php
   
                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
      		       $sql = "select id, name, type FROM humans WHERE type like 'telegram:channel' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <strong>TELEGRAM CHANNELS LIST</strong>
                            </div>
                        </div>
                    </div>

                    <div id='discord' class='areasform text-uppercase text-center'>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                        <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-info btn-icon-split mr-2 mt-1">
                            <span class="icon text-white-50">
                                <i class="fab fa-telegram"></i>
                            </span>
                            <span class="text" style="width:250px;"><?php echo $row['name']; ?></span>
                        </a>

                        <?php } ?>

                    </div>

                    <?php } ?>

                    <?php } ?>

                    <!--  Webhooks -->

                    <?php

                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                       $sql = "select id, name, type FROM humans WHERE type like 'webhook' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <strong>WEBHOOKS LIST</strong>
                            </div>
                        </div>
                    </div>

                    <div id='discord' class='areasform text-uppercase text-center'>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                        <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-secondary btn-icon-split mr-2 mt-1">
                            <span class="icon text-white-50">
                                <font size=1>WH</font>
                            </span>
                            <span class="text" style="width:250px;"><?php echo $row['name']; ?></span>
                        </a>

                        <?php } ?>

                    </div>

                    <?php } ?>

                    <?php } ?>



                </div>

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

    <br>
</body>

</html>