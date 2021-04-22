<?php

include "header.php";

if (!isset($_GET['type'])) { $_GET['type'] = "display"; }
if (!isset($_GET['page'])) { $_GET['page'] = "dashboard"; }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    if (@$gAnalyticsId != "") {
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
    <link href="css/nav.css?v=<?=time();?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="search_mons.js?v=<?=time();?>"></script>
    <script type="text/javascript" src="js/functions.js?v=<?=time();?>"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="node_modules/jquery.easing/jquery.easing.min.js"></script>

</head>

<!-- INCLUDE NAV BAR -->
<?php include "include/nav.php"; ?>

<html>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid col-lg-8 col-md-12">

                <?php echo @$admin_alarm; ?>
                    <!-- Profile Settings Modal -->
                    <?php include "./modal/profile_settings_modal.php"; ?>

                    <!-- Success Alerts-->
                    <?php include "include/return_messages.php"; ?>
       	            <?php include 'pages/'.$_GET['type'].'/'.$_GET['page'].'.php'; ?>

                <!-- End of Page Content -->
	        </div>

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

</body>
</html>


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<?php include "./modal/logout_modal.php"; ?>

<!-- Custom scripts for all pages-->
<script src="js/scripts.js?v=<?=time();?>"></script>
<script src="js/nav.js?v=<?=time();?>"></script>
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

