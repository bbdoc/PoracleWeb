<?php
include "./header.php";

# No Migration Allowed if Telegram is not Enabled

if ( @$enable_telegram <> "True" ) {
	header("Location: $redirect_url");
        exit();
}

# Only Allow Migration if SESSION type is set to User

if ( $_SESSION['type'] <> "discord:user" && $_SESSION['type'] <> "telegram:user") {
        header("Location: $redirect_url");
        exit();
}

# EXECUTE MIGRATION ON SUBMIT 

if (isset($_POST['migrate']) && $_POST['migration_type'] == "discord2telegram" ) {

  $source_id=$_POST['discord_id'];
  $target_id=$_POST['telegram_id'];
  $target_user=$_POST['telegram_user'];
  $target_avatar=$_POST['telegram_avatar'];
  $target_type="telegram:user";

  # DISABLE FOREIGN KEY CHECKS
  $sql = "SET foreign_key_checks = 0;";
  $result = $conn->query($sql);

  # UPDATE HUMANS TABLE
  $stmt = $conn->prepare("UPDATE humans set id = ?, type = ?, name = ? where id = ?;");
  $rs = $stmt->bind_param("ssss", $target_id, $target_type, $target_user, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE MONSTERS TABLE
  $stmt = $conn->prepare("UPDATE monsters set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE EGG TABLE
  $stmt = $conn->prepare("UPDATE egg set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE RAID TABLE
  $stmt = $conn->prepare("UPDATE raid set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE QUEST TABLE
  $stmt = $conn->prepare("UPDATE quest set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE INVASION TABLE
  $stmt = $conn->prepare("UPDATE invasion set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE WEATHER TABLE
  $stmt = $conn->prepare("UPDATE weather set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # ENABLE FOREIGN KEY CHECKS
  $sql = "SET foreign_key_checks = 1;";
  $result = $conn->query($sql);

  # CONNECT NEW USER
  $_SESSION['id']=$target_id;
  $_SESSION['username']=$target_user;
  $_SESSION['avatar']=$target_avatar;
  $_SESSION['type']=$target_type;

  # UPDATE ADMIN ID
  unset($_SESSION['admin_id']);
  if (isset($admin_id)) {
        $admins = explode(",", $admin_id);
  }
  foreach ($admins as &$admin) {
    if ($_SESSION['id'] == $admin)
    {
        $_SESSION['admin_id'] = $_SESSION['id'];
        $_SESSION['admin_username'] = $_SESSION['username'];
        $_SESSION['admin_dbname'] = $_SESSION['dbname'];
        $_SESSION['admin_type'] = $_SESSION['type'];
     }
  }

  header("Location: $redirect_url?return=success_migrate&mig_source=Discord&mig_target=Telegram");
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
			<center>
                        <?php echo i8ln("Migrate Your Account"); ?>
			</center>
                    </h4>
                    <hr>

		    <center>

                    <!-- Current Connection -->

		    <p class="mb-2"><?php echo i8ln("You are currently connected as"); ?><p>

                    <button class="btn btn-<?php if ($_SESSION['type']=='discord:user') { echo "primary"; } else { echo "info"; } ?> btn-icon-split m-1">
                        <span class="icon text-white-50">
                        <font size=6><i class="fab fa-<?php echo rtrim($_SESSION['type'], ':user');?>"></i></font>
                        </span>
                        <span class="text" style="min-width: 250px;"><?php echo $_SESSION['username']; ?><br>[ <?php echo $_SESSION['id']; ?> ]</span>
                    </button>

                    <!--  Target Connection -->
                    
		    <?php if ($_SESSION['type']=='discord:user') { ?>


		       <?php if (isset($_GET['id'])) { ?>
                       <?php if (!isset($_GET['username'])) { $_GET['username'] = $_GET['first_name']." ".$_GET['last_name']; } ?>

		       <?php 
                          # CHECK IF TARGET USER ALREADY EXIST
                          $stmt = $conn->prepare("SELECT * from humans WHERE id = ?");
                          $stmt->bind_param("s", $_GET['id']);
                          $stmt->execute();
                          $stmt->store_result();
			  if ( $stmt->num_rows > 0 ) { 
	               ?>

                          <div class="alert alert-danger fade show" role="alert" style='width:300px;'>
                             <?php echo i8ln("User Already Exist"); ?>
			  </div>
                          <div class="alert alert-danger fade show" role="alert" style='width:300px;'>
                             <?php echo i8ln("Cannot Migrate to an existing User"); ?>
			  </div>
			     <a href='<?php echo $redirect_url; ?>'><button class="btn btn-primary" style="width:300px;">Back to PoracleWeb</button></a>

                       <?php } else { ?>

	   	          <p class="mb-2"><?php echo i8ln("Your account will be migrated to"); ?><p>

                          <button class="btn btn-info btn-icon-split m-1">
                              <span class="icon text-white-50">
                              <font size=6><i class="fab fa-telegram"></i></font>
                              </span>
			      <span class="text" style="min-width: 250px;">
			         <?php echo $_GET['username'] ?><br>[ <?php echo $_GET['id']; ?> ]
                              </span>
                          </button>

                          <hr>
		          <p class="mb-2"><?php echo i8ln("Migration is definitive and cannot be reversed");?>!<p>
		          <form action='migrate.php' method='POST'>
                          <input type='hidden' id='migration_type' name='migration_type' value='discord2telegram'>
                          <input type='hidden' id='discord_id' name='discord_id' value='<?php echo $_SESSION['id'];?>'>
                          <input type='hidden' id='telegram_id' name='telegram_id' value='<?php echo $_GET['id'];?>'>
                          <input type='hidden' id='telegram_user' name='telegram_user' value='<?php echo $_GET['username'];?>'>
                          <input type='hidden' id='telegram_avatar' name='telegram_avatar' value='<?php echo $_GET['photo_url'];?>'>
		          <input class="btn btn-success" type='submit' name='migrate' id="migrate" value='<?php echo i8ln("CONFIRM ACCOUNT MIGRATION"); ?>'>
                          </form>

		       <?php } ?>

                       <?php $stmt->close(); ?> 
		       <?php } else { ?>

		       <hr>
		       <?php echo i8ln("Start account migration by"); ?><br>
		       <?php echo i8ln("logging into your target telegram account"); ?><br><br>
                       <script async src="https://telegram.org/js/telegram-widget.js?14"
                            data-telegram-login="<?php echo $telegram_bot; ?>"
                            data-size="medium"
                            data-auth-url="<?php echo $redirect_url; ?>/migrate.php"
                            data-request-access="write">
                       </script>

		       <?php } ?>

		    <?php } ?>
		    <?php if ($_SESSION['type']=='telegram:user') { ?>
		    <?php echo i8ln("Sorry Migration From Telegram to Discord is not available yet"); ?>.

		    <?php } ?>


		    </center>

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
