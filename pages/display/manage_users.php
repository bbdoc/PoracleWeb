<?php
if ( !isset($_SESSION['admin_id']) && !isset($_SESSION['users_admin']) ) { 
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
    <center><?php echo i8ln("Users Management"); ?></center>
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

<?php } else if ( isset($_SESSION['delegated_id']) && $_SESSION['delegated_id'] <> $_SESSION['id']) { ?>

<hr>
<center>
    <a href="admin_connect.php?id=<?php echo $_SESSION['delegated_id']; ?>">
        <button type="button" class="btn btn-success" style="width:300px;">
            <?php echo i8ln("Back to own Account"); ?>
        </button>
    </a>
</center>

<?php } ?>


<!-- User Access -->

<hr>
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

   foreach (poracle_all_apis() as $instance => $api) {

      try {
         $humans = array();
         foreach (array('discord:user' => 'DISCORD', 'telegram:user' => 'TELEGRAM') as $type => $label) {
            $list = $api->get('/api/v2/humans', array('type' => $type))['humans'];
            usort($list, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
            $humans[$type] = $list;
         }
      } catch (PoracleApiException $e) {
         echo "<hr><div class='alert alert-danger text-center' role='alert'>" . htmlspecialchars(i8ln("Could not connect to the API") . " ($instance)") . "</div>";
         continue;
      }

      foreach (array('discord:user' => 'DISCORD', 'telegram:user' => 'TELEGRAM') as $type => $label) {
      ?>

      <?php if (count($humans[$type]) <> 0) { ?>

         <hr>

         <!-- Heading -->
         <div class="row">
           <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
              <strong><?php echo i8ln("USERS LIST"); ?> - <?php echo $label; ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".htmlspecialchars($instance); } ?>
              </div>
           </div>
         </div>

         <div class='text-uppercase text-center'>

         <?php foreach ($humans[$type] as $row) { ?>
            <a href="admin_connect.php?id=<?php echo htmlspecialchars(rawurlencode($row['id'])); ?>"
               class="btn btn-<?php if ($row['type']=='discord:user') { echo "primary"; } else { echo "info"; } ?> btn-icon-split m-1">
            <span class="icon text-white-50"> <i class="fab fa-<?php echo rtrim($row['type'], ':user');?>"></i> </span>
            <span class="text" style="min-width: 130px;"><font size=1><?php echo htmlspecialchars($row['name']); ?></font></span>
            </a>
         <?php } ?>

      </div>

      <?php } ?>

      <?php } ?>

   <?php } ?>

<?php } ?>
