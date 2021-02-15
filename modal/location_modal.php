<?php

if ($latitude == "0.0000000000" && $longitude == "0.0000000000") {
?>
<div class="alert alert-danger" role="alert">
    <?php echo i8ln("Your Location is not set yet!"); ?><br>
    <?php echo i8ln("It can be set hereunder"); ?>
</div>

<?php
} else if (isset($mapURL) && $mapURL <> "") {
?>
<div class="alert alert-success" role="alert">
    <?php echo i8ln("Your Location is set to"); ?><br>
    <?php echo "<b>".$address."</b><br>"; ?>
    <?php echo "[ ".round($latitude, 4); ?>, <?php echo round($longitude, 4)." ]"; ?>
</div>

<?php
    $mapURL = str_replace('#LAT#', $latitude, $mapURL);
    $mapURL = str_replace('#LON#', $longitude, $mapURL);
    ?>
<div class='text-center'>
    <img src='<?php echo $mapURL; ?>' width=300>
</div>
<?php
}

?>

<hr>

<div class="row">
    <div class="col">
        <div class="alert alert-secondary text-center" role="alert">
            <strong><?php echo i8ln("Update My Location"); ?></strong>
        </div>
    </div>
</div>

<form action='./actions/set_location.php' method='POST'>

<div class='mb-3' id='dvStreet'>
    <input type='text' class='form-control form-control-lg' id='street' name='street' placeholder='<?php echo i8ln("Street Name") ?>'>
</div>
<div class='mb-3' id='dvCity'>
    <input type='text' class='form-control form-control-lg' id='city' name='city' placeholder='<?php echo i8ln("City") ?>'>
</div>

<div class="modal-footer">
    <input class="btn btn-primary" type='submit' name='update' value='<?php echo i8ln("Update"); ?>'>
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo i8ln("Cancel"); ?></button>
</div>

</form>
