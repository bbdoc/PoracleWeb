<?php
if (!isset($_SESSION['admin_id'])) { 
	header("Location: $redirect_url"); 
	exit();
} 

$num_dbs=0;
$dbnames = explode(",", $dbname);
foreach ($dbnames as &$db) {
	$num_dbs=$num_dbs+1;
}

?>


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
                    
                    if (@$admin_disable_userlist <> "True" ) {

                    $dbnames = explode(",", $dbname);
                    foreach ($dbnames as &$db) {

                       $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                       $sql = "select id, name, type FROM humans WHERE type like '%:user' ORDER by type,name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("USERS LIST"); ?></strong>
                                <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
                            </div>
                        </div>
                    </div>

                    <div class='text-uppercase text-center'>

			<?php while ($row = $result->fetch_assoc()) { ?>

                        <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
			class="btn btn-<?php if ($row['type']=='discord:user') { echo "primary"; } else { echo "info"; } ?> btn-icon-split m-1">
			    <span class="icon text-white-50">
			    <i class="fab fa-<?php echo rtrim($row['type'], ':user');?>"></i>
                            </span>
                            <span class="text" style="min-width: 130px;"><font size=1><?php echo $row['name']; ?></font></span>
                        </a>

                        <?php } ?>
                    </div>

                    <?php } ?>
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
				<strong><?php echo i8ln("DISCORD CHANNELS LIST"); ?></strong>
                                <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
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
      		       $sql = "select id, name, type FROM humans WHERE type in ('telegram:channel','telegram:group') ORDER by name";
                       $result = $conn->query($sql);
                       ?>

                    <?php if ($result->num_rows <> 0) { ?>

                    <hr>

                    <!-- Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("TELEGRAM CHANNELS LIST"); ?></strong>
                                <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
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
				<strong><?php echo i8ln("WEBHOOKS LIST"); ?></strong>
                                <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
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

