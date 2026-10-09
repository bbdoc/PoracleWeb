<?php
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['channels_admin'])) {
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
    <center><?php echo i8ln("Channel Management"); ?></center>
</h4>

<!-- BACK TO OWN ACCOUNT -->

<?php  if ( isset($_SESSION['admin_id']) && $_SESSION['admin_id'] <> $_SESSION['id']) { ?>

<hr>
<center>
    <a href="admin_connect.php?id=<?php echo $_SESSION['admin_id']; ?>">
        <button type="button" class="btn btn-success" style="width:300px;">
            <?php echo i8ln("Back to own Account"); ?>
        </button>
    </a>
</center>

<?php  } else if ( isset($_SESSION['delegated_id']) && $_SESSION['delegated_id'] <> $_SESSION['id']) { ?>

<hr>
<center>
    <a href="admin_connect.php?id=<?php echo $_SESSION['delegated_id']; ?>">
        <button type="button" class="btn btn-success" style="width:300px;">
            <?php echo i8ln("Back to own Account"); ?>
        </button>
    </a>
</center>

<?php } ?>

<!-- Discord Channels -->

<?php

if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || count($_SESSION['delegated_channels']['discord']['channels']) > 0 ) {

   foreach (poracle_all_apis() as $instance => $api) {

      try {
         $humans = array();
         foreach (array('discord:channel') as $type) {
            $humans = array_merge($humans, $api->get('/api/v2/humans', array('type' => $type))['humans']);
         }
         usort($humans, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
      } catch (PoracleApiException $e) {
         echo "<hr><div class='alert alert-danger text-center' role='alert'>" . htmlspecialchars(i8ln("Could not connect to the API") . " ($instance)") . "</div>";
         continue;
      }
      ?>

   <?php if (count($humans) <> 0) { ?>

      <hr>

      <!-- Heading -->
      <div class="row">
          <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
                  <strong><?php echo i8ln("DISCORD CHANNELS LIST"); ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".htmlspecialchars($instance); } ?>
              </div>
          </div>
      </div>

      <div id='discord' class='areasform text-uppercase text-center'>

          <?php foreach ($humans as $row) { ?>

             <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || in_array($row['id'],$_SESSION['delegated_channels']['discord']['channels']) ) { ?>

             <a href="admin_connect.php?id=<?php echo htmlspecialchars(rawurlencode($row['id'])); ?>"
                 class="btn btn-primary btn-icon-split mr-2 mt-1">
                 <span class="icon text-white-50">
                     <i class="fab fa-discord"></i>
                 </span>
                 <span class="text" style="width:250px;">
                    <?php echo htmlspecialchars($row['name']); ?>
                    <?php
                      if ( isset($row['notes']) ) {
                         echo "<br><span class='badge badge-light'>".htmlspecialchars($row['notes'])."</span>";
                      }
                    ?>
                    <?php if (@$admin_channel_id == "True") { ?>
                       <font size=2><br><?php echo htmlspecialchars($row['id'])."</font>"; ?>
                    <?php } ?>
                 </span>
             </a>

             <?php } ?>

          <?php } ?>
      </div>

      <?php } ?>

   <?php } ?>

<?php } ?>

<!-- Telegram Channels -->

<?php

if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || count($_SESSION['delegated_channels']['telegram']['channels']) > 0 ) {

   foreach (poracle_all_apis() as $instance => $api) {

      try {
         $humans = array();
         foreach (array('telegram:channel', 'telegram:group') as $type) {
            $humans = array_merge($humans, $api->get('/api/v2/humans', array('type' => $type))['humans']);
         }
         usort($humans, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
      } catch (PoracleApiException $e) {
         echo "<hr><div class='alert alert-danger text-center' role='alert'>" . htmlspecialchars(i8ln("Could not connect to the API") . " ($instance)") . "</div>";
         continue;
      }
      ?>

   <?php if (count($humans) <> 0) { ?>

      <hr>

      <!-- Heading -->
      <div class="row">
          <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
                  <strong><?php echo i8ln("TELEGRAM CHANNELS LIST"); ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".htmlspecialchars($instance); } ?>
              </div>
          </div>
      </div>

      <div id='discord' class='areasform text-uppercase text-center'>

          <?php foreach ($humans as $row) { ?>

             <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || in_array($row['id'],$_SESSION['delegated_channels']['telegram']['channels']) ) { ?>

             <a href="admin_connect.php?id=<?php echo htmlspecialchars(rawurlencode($row['id'])); ?>"
                 class="btn btn-info btn-icon-split mr-2 mt-1">
                 <span class="icon text-white-50">
                     <i class="fab fa-telegram"></i>
                 </span>
                 <span class="text" style="width:250px;">
                    <?php echo htmlspecialchars($row['name']); ?>
                    <?php
                      if ( isset($row['notes']) ) {
                         echo "<br><span class='badge badge-light'>".htmlspecialchars($row['notes'])."</span>";
                      }
                    ?>
                    <?php if (@$admin_channel_id == "True") { ?>
                       <font size=2><br><?php echo htmlspecialchars($row['id'])."</font>"; ?>
                    <?php } ?>
                 </span>
             </a>

             <?php } ?>

          <?php } ?>
      </div>

      <?php } ?>

   <?php } ?>

<?php } ?>

<!-- Webhooks -->

<?php

if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || count($_SESSION['delegated_channels']['discord']['webhooks']) > 0 ) {

   foreach (poracle_all_apis() as $instance => $api) {

      try {
         $humans = array();
         foreach (array('webhook') as $type) {
            $humans = array_merge($humans, $api->get('/api/v2/humans', array('type' => $type))['humans']);
         }
         usort($humans, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
      } catch (PoracleApiException $e) {
         echo "<hr><div class='alert alert-danger text-center' role='alert'>" . htmlspecialchars(i8ln("Could not connect to the API") . " ($instance)") . "</div>";
         continue;
      }
      ?>

   <?php if (count($humans) <> 0) { ?>

      <hr>

      <!-- Heading -->
      <div class="row">
          <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
                  <strong><?php echo i8ln("WEBHOOKS LIST"); ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".htmlspecialchars($instance); } ?>
              </div>
          </div>
      </div>

      <div id='discord' class='areasform text-uppercase text-center'>

          <?php foreach ($humans as $row) { ?>

             <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || in_array($row['name'],$_SESSION['delegated_channels']['discord']['webhooks']) ) { ?>

             <a href="admin_connect.php?id=<?php echo htmlspecialchars(rawurlencode($row['id'])); ?>"
                 class="btn btn-secondary btn-icon-split mr-2 mt-1">
                 <span class="icon text-white-50">
                     <font size=1>WH</font>
                 </span>
                 <span class="text" style="width:250px;">
                    <?php echo htmlspecialchars($row['name']); ?>
                    <?php
                      if ( isset($row['notes']) ) {
                         echo "<br><span class='badge badge-light'>".htmlspecialchars($row['notes'])."</span>";
                      }
                    ?>
                    <?php if (@$admin_channel_id == "True") { ?>
                       <font size=2><br><?php echo htmlspecialchars($row['id'])."</font>"; ?>
                    <?php } ?>
                 </span>
             </a>

             <?php } ?>

          <?php } ?>
      </div>

      <?php } ?>

   <?php } ?>

<?php } ?>
