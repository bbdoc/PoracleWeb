<?php

if ($latitude == "0.0000000000" && $longitude == "0.0000000000") {
?>
<div class="alert alert-danger" role="alert">
    <?php echo i8ln("Your Location is not set yet!"); ?><br>
    <?php echo i8ln("It can be set hereunder"); ?>
</div>

<?php 
} else {

   // Get Map Image URL from API
   $map = getMiniMap($latitude, $longitude, $row['distance']);

?>

<div class="modal-header">
   <h5 class="modal-title m-2" id="profileSettingsModalLongTitle"><?php echo i8ln("Distance Display"); ?></h5>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
   </button>
</div>
<br>

<?php if (@$disable_nominatim <> "True") { ?>
<div class="alert alert-success" role="alert">
    <?php echo i8ln("Your Location is set to"); ?><br>
    <?php $address=get_address($latitude, $longitude); ?>
    <?php echo "<b>".$address."</b><br>"; ?>
    <?php echo "[ ".round($latitude, 4); ?>, <?php echo round($longitude, 4)." ]"; ?>
</div>
<?php } ?>

<div class="alert alert-success" role="alert">
    <?php echo i8ln("Configured Distance:"); ?> <br>
    <?php echo "<b>".$row['distance']." ".i8ln("meters around your position")."</b><br>"; ?>
</div>


<div class='text-center'>
    <img src='<?php echo $map; ?>' width=350>
</div>
<?php
}

?>

