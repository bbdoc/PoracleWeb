<?php

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

?>

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
                        <span class="text" style="min-width: 250px;"><?php echo htmlspecialchars($_SESSION['username']); ?><br>[ <?php echo htmlspecialchars($_SESSION['id']); ?> ]</span>
                    </button>

                    <!--  Target Connection -->
                    
		    <?php if ($_SESSION['type']=='discord:user') { ?>


		       <?php if (isset($_GET['id'])) { ?>

		       <?php
                          # VERIFY THE TELEGRAM LOGIN, THEN CHECK THE TARGET USER DOES NOT EXIST
                          unset($_SESSION['migrate_telegram']);
                          $tg_valid = telegram_auth_valid($_GET, $telegram_bot_token ?? '', time());
                          $tg_exists = false;
                          $tg_error = null;
                          if ($tg_valid) {
                             $tg_user = array(
                                'id' => (string) $_GET['id'],
                                'username' => $_GET['username'] ?? trim(($_GET['first_name'] ?? '') . ' ' . ($_GET['last_name'] ?? '')),
                                'avatar' => $_GET['photo_url'] ?? '',
                             );
                             try {
                                poracle_api()->get(poracle_human_path($tg_user['id']));
                                $tg_exists = true;
                             } catch (PoracleApiException $e) {
                                if ($e->status !== 404) {
                                   $tg_error = $e->userMessage();
                                }
                             }
                          }
                          if (!$tg_valid) {
	               ?>

                          <div class="alert alert-danger fade show" role="alert" style='width:300px;'>
                             <?php echo i8ln("Invalid Telegram login, please try again"); ?>
			  </div>
			     <a href='<?php echo $redirect_url; ?>?type=display&page=migrate'><button class="btn btn-primary" style="width:300px;"><?php echo i8ln("Back"); ?></button></a>

                       <?php } else if ($tg_error !== null) { ?>

                          <div class="alert alert-danger fade show" role="alert" style='width:300px;'>
                             <?php echo htmlspecialchars($tg_error); ?>
			  </div>
			     <a href='<?php echo $redirect_url; ?>'><button class="btn btn-primary" style="width:300px;">Back to PoracleWeb</button></a>

                       <?php } else if ($tg_exists) { ?>

                          <div class="alert alert-danger fade show" role="alert" style='width:300px;'>
                             <?php echo i8ln("User Already Exist"); ?>
			  </div>
                          <div class="alert alert-danger fade show" role="alert" style='width:300px;'>
                             <?php echo i8ln("Cannot Migrate to an existing User"); ?>
			  </div>
			     <a href='<?php echo $redirect_url; ?>'><button class="btn btn-primary" style="width:300px;">Back to PoracleWeb</button></a>

                       <?php } else { $_SESSION['migrate_telegram'] = $tg_user; ?>

	   	          <p class="mb-2"><?php echo i8ln("Your account will be migrated to"); ?><p>

                          <button class="btn btn-info btn-icon-split m-1">
                              <span class="icon text-white-50">
                              <font size=6><i class="fab fa-telegram"></i></font>
                              </span>
			      <span class="text" style="min-width: 250px;">
			         <?php echo htmlspecialchars($tg_user['username']); ?><br>[ <?php echo htmlspecialchars($tg_user['id']); ?> ]
                              </span>
                          </button>

                          <hr>
		          <p class="mb-2"><?php echo i8ln("Migration is definitive and cannot be reversed");?>!<p>
		          <form action='./actions/migrate.php' method='POST'>
                          <input type='hidden' id='migration_type' name='migration_type' value='discord2telegram'>
		          <input class="btn btn-success" type='submit' name='migrate' id="migrate" value='<?php echo i8ln("CONFIRM ACCOUNT MIGRATION"); ?>'>
                          </form>

		       <?php } ?>

		       <?php } else { ?>

		       <hr>
		       <?php echo i8ln("Start account migration by"); ?><br>
		       <?php echo i8ln("logging into your target telegram account"); ?><br><br>
                       <script async src="https://telegram.org/js/telegram-widget.js?14"
                            data-telegram-login="<?php echo $telegram_bot; ?>"
                            data-size="medium"
                            data-auth-url="<?php echo $redirect_url; ?>/?type=display&page=migrate"
                            data-request-access="write">
                       </script>

		       <?php } ?>

		    <?php } ?>
		    <?php if ($_SESSION['type']=='telegram:user') { ?>
                    <hr>
		    <?php echo i8ln("Sorry Migration From Telegram to Discord is not available yet"); ?>.

		    <?php } ?>


		    </center>

