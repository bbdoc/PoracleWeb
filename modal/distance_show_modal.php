<?php

if ( $row['distance'] < 750 ) { $zoom="14"; }
else if ( $row['distance'] < 1500  ) { $zoom="13"; }
else if ( $row['distance'] < 3000  ) { $zoom="12"; }
else if ( $row['distance'] < 6000  )  { $zoom="11"; }
else if ( $row['distance'] < 12000 )  { $zoom="10"; }
else if ( $row['distance'] < 24000 )  { $zoom="9"; }
else { $zoom="8"; }

$map=$mapPoracleWeb;
$map.="?img=$redirect_url/img/position.png";
$map.="&latitude=$latitude";
$map.="&longitude=$longitude";
$map.="&distance=".$row['distance'];
$map.="&zoom=$zoom";

if ($latitude == "0.0000000000" && $longitude == "0.0000000000") {
?>
<div class="alert alert-danger" role="alert">
    <?php echo i8ln("Your Location is not set yet!"); ?><br>
    <?php echo i8ln("It can be set hereunder"); ?>
</div>

<?php
} else if (isset($mapURL) && $mapURL <> "") {
?>

<div class="modal-header">
   <h5 class="modal-title m-2" id="profileSettingsModalLongTitle"><?php echo i8ln("Distance Display"); ?></h5>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
   </button>
</div>
<br>

<div class="alert alert-success" role="alert">
    <?php echo i8ln("Your Location is set to"); ?><br>
    <?php echo "<b>".$address."</b><br>"; ?>
    <?php echo "[ ".round($latitude, 4); ?>, <?php echo round($longitude, 4)." ]"; ?>
</div>

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

