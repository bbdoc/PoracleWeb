<!DOCTYPE html>

<?php include "./config.php"; ?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?> - Login</title>

    <link rel="icon" type="image/x-icon" href="favicon.png" />

    <!-- Custom fonts for this template-->
    <link href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/custom-bootstrap.css" rel="stylesheet">

</head>

<body class="bg-gradient-dark">

    <div class="container">

        <?php
        if (isset($_GET['return']) && $_GET['return'] == 'success_logout') {
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" id='dvAlertTypeAll'>
	    <?php echo i8ln("You have been successfully logged out"); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        }
        ?>

        <?php
        if (isset($_GET['return']) && $_GET['return'] == 'error_api_nok') {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id='dvAlertTypeAll'>
            <?php echo i8ln("API returned an error. Please ask admin to check Poracle Instance"); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        }
        ?>

        <?php
        if (isset($_GET['return']) && $_GET['return'] == 'error_no_api') {
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id='dvAlertTypeAll'>
            <?php echo i8ln("Could not connect to API. Please ask admin to check server config"); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        }
        ?>

        <!-- Outer Row -->
        <div class="row justify-content-center text-center">

            <div class="col-xl-6 col-lg-6 col-md-6 text-center">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h3 text-gray-900 mb-1"><?php echo $title; ?></h1>
                                    </div>
				    <p class="text-gray-900 mb-4"><?php echo i8ln("ALARM CONFIGURATION"); ?></p>

				    <p class="mb-3"><small><?php echo i8ln("LOGIN WITH DISCORD"); ?></small></p>

                                    <a href="./discord_auth.php?action=login">
                                        <i class="fab fa-discord fa-5x fa-fw mr-2 text-white-400"></i>
                                    </a>
				    <!-- <a href='./discord_auth.php?action=login'><img width=100 src='./img/discord.jpg'></a> -->
                                    <?php if ( isset($enable_telegram) && $enable_telegram == 'True' ){ ?>
				    <hr>
				    <p class="mb-3"><small><?php echo i8ln("LOGIN WITH TELEGRAM"); ?></small></p>
				    <script async src="https://telegram.org/js/telegram-widget.js?14" 
	                                    data-telegram-login="<?php echo $telegram_bot; ?>" 
					    data-size="medium" 
					    data-auth-url="<?php echo $redirect_url; ?>/telegram_auth.php" 
                                            data-request-access="write">
				    </script>
                                    <?php } ?>
				</div>

                            </div>
			</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="node_modules/jquery.easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/scripts.js"></script>

</body>

</html>
