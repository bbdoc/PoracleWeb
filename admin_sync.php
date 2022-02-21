<?php
include "./header.php";

# Page is Only Available to Admins

if (!isset($_SESSION['admin_id']) ) { 
	header("Location: $redirect_url"); 
	exit();
} 

# Page is only available if on a Channel

if ( $_SESSION['type'] <> 'telegram:channel' && $_SESSION['type'] <> 'telegram:group' && $_SESSION['type'] <> 'discord:channel' && $_SESSION['type'] <> 'webhook') {
        header("Location: $redirect_url");
        exit();
}

$num_dbs=0;
$dbnames = explode(",", $dbname);
foreach ($dbnames as &$db) {
	$num_dbs=$num_dbs+1;
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
    <link href="css/nav.css?v=<?=time();?>" rel="stylesheet">
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

                <?php include "include/nav.php" ?>

                <!-- Begin Page Content -->
                <div class="container-fluid col-lg-8 col-md-12">

                    <!-- Title -->

                    <h4 class="modal-title m-2">
                        <center><?php echo i8ln("Channel Synchronization"); ?></center><hr>
                    </h4>

                    <h6 class="modal-title m-2">
                        <p><center><?php echo i8ln("Please select channels you want to synchronize with current channel"); ?></center></p>
			<p><center><font color="darkred"><strong>
                           <?php echo i8ln("This will DELETE AND REPLACE all current tracking from ALL chosen channels"); ?>
                        </strong></font></center></p>
                    </h6>
		    <form action='./actions/channel_sync.php' method='POST'>

                    <!-- Discord Channels -->

                    <?php

                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                       $sql = "select id, name, type FROM humans WHERE type like 'discord:channel' AND id <> '".$_SESSION['id']."' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <?php if ($num_dbs > 1) { echo $db."<br>"; } ?>
				<strong><?php echo i8ln("DISCORD CHANNELS TO SYNC WITH"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div id='discord' class='areasform text-uppercase text-center'>

                        <ul>
			<?php while ($row = $result->fetch_assoc()) { 
                           $target_id=$db."|".$row['id'];
                        ?>

			<li class="btn btn-primary btn-icon-split mr-2 mt-1">
			    <span class="icon text-white-50">
                                <input type='checkbox' name='target_<?php echo $target_id; ?>' id='target_<?php echo $target_id; ?>'>
                                <label for='target_<?php echo $target_id; ?>'><font style='font-size:12px;'><i class="fab fa-discord"></i></font></label>
                            </span>
                            <span class="text" style="width:250px;"><?php echo $row['name']; ?></span>
                        </li>

			<?php } ?>
                        </ul>
  
                    </div>

                    <?php } ?>

                    <?php } ?>


                    <!--  Telegram Channels -->

                    <?php
   
                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
      		       $sql = "select id, name, type FROM humans WHERE type in ('telegram:channel','telegram:group') AND id <> '".$_SESSION['id']."' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <?php if ($num_dbs > 1) { echo $db."<br>"; } ?>
				<strong><?php echo i8ln("TELEGRAM CHANNELS TO SYNC WITH"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div id='discord' class='areasform text-uppercase text-center'>

                        <ul>      
                        <?php while ($row = $result->fetch_assoc()) { 
                           $target_id=$db."|".$row['id'];
                        ?>

                        <li class="btn btn-info btn-icon-split mr-2 mt-1">
                            <span class="icon text-white-50">
                                <input type='checkbox' name='target_<?php echo $target_id; ?>' id='target_<?php echo $target_id; ?>'>
                                <label for='target_<?php echo $target_id; ?>'><font style='font-size:12px;'><i class="fab fa-telegram"></i></font></label>
                            </span>
                            <span class="text" style="width:250px;"><?php echo $row['name']; ?></span>
                        </li>


			<?php } ?>
                        </ul>

                    </div>

                    <?php } ?>

                    <?php } ?>

                    <!-- Webhooks -->

                    <?php

                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                       $sql = "select id, name, type FROM humans WHERE type like 'webhook' AND id <> '".$_SESSION['id']."' ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
                                <?php if ($num_dbs > 1) { echo $db."<br>"; } ?>
                                <strong><?php echo i8ln("WEBHOOKS TO SYNC WITH"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div id='discord' class='areasform text-uppercase text-center'>

                        <ul>
                        <?php while ($row = $result->fetch_assoc()) {
                           $target_id=$db."|".$row['id'];
                        ?>

                        <li class="btn btn-secondary btn-icon-split mr-2 mt-1">
                            <span class="icon text-white-50">
                                <input type='checkbox' name='target_<?php echo $target_id; ?>' id='target_<?php echo $target_id; ?>'>
                                <label for='target_<?php echo $target_id; ?>'><font size="1">WH</font></label>
                            </span>
                            <span class="text" style="width:250px;"><?php echo $row['name']; ?></span>
                        </li>

                        <?php } ?>
                        </ul>

                    </div>

                    <?php } ?>

                    <?php } ?>

		    <center><hr>
		    <input class="btn" style="background-color:darkred; color:white;" type='submit' name='sync' id="sync" 
                           value='<?php echo i8ln("CONFIRM CHANNEL SYNCHRONIZATION"); ?>'>
		    </center><br>
		    </form>

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
    <script src="js/nav.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <br>
</body>

</html>
