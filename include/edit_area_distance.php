<?php
   
if ( $row['distance'] == 0 && $disable_areas <> "True" ) {
   $area_check="checked";
   $distance_check="";
   $style="style='display:none;'";
} else {
   $area_check="";
   $distance_check="checked";
   $style="";
}
   
?>
   
<?php if ( $disable_areas <> "True"  && $disable_location <> "True" ) { ?>
   <div class="input-group">
       <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
       <label class="btn btn-secondary">
           <input type="radio" name="use_areas_pkmn" id="use_areas_<?php echo $unique_id; ?>" value="areas" <?php echo $area_check; ?>
           onclick="areas('<?php echo $unique_id; ?>')">
           <?php echo i8ln("Use Areas"); ?>
       </label>
       <label class="btn btn-secondary mr-2">
           <input type="radio" name="use_areas_pkmn" id="use_areas_<?php echo $unique_id; ?>" value="distance" <?php echo $distance_check; ?>
           onclick="areas('<?php echo $unique_id; ?>')">
           <?php echo i8ln("Set Distance"); ?>
       </label>
       </div>
   </div>
<?php }  else if ( $disable_location <> "True" && $disable_areas == "True") { ?>
   <div class="input-group">
       <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
       <label class="btn btn-secondary mr-2">
           <input type="radio" name="use_areas_pkmn" id="use_areas_<?php echo $unique_id; ?>" value="distance" checked>
           <?php echo i8ln("Set Distance"); ?>
       </label>
       </div>
   </div>
<?php } ?> 
   
   
<?php if ( $disable_location == "True" ) { ?>
   <input type="hidden" id='distance_<?php echo $unique_id; ?>' name='distance' value='0'> 
<?php } ?>
   
<?php if ( $disable_location <> "True" ) { ?>
   <div class="input-group mt-2">
       <input type="number" id='distance_<?php echo $unique_id; ?>' name='distance' value='<?php echo $row['distance'] ?>' <?php echo $style; ?>
           min='0' max='<?php echo $_SESSION['maxDistance']; ?>' class="form-control text-center">
       <div class="input-group-append" id="distance_label_<?php echo $unique_id; ?>" <?php echo $style; ?>>
           <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
       </div>
   </div>
<?php } ?>
   
