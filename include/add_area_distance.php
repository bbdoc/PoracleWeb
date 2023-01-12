
<?php 

if ( $default_distance == 0 ) { 
	$display = "none"; $area_checked = "checked"; $distance_checked = "";
} else {
	$display = "block"; $area_checked = ""; $distance_checked = "checked";
}

?>

<?php if (@$disable_location <> "True" && @$disable_areas <> "True" ) { ?>

   <div class="form-row align-items-center">
       <div class="col-sm-12 my-1">
           <div class="input-group">
               <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
               <label class="btn btn-secondary">
                   <input type="radio" name="use_areas" id="use_areas" value="areas" <?php echo $area_checked; ?> onclick="areas_add()"><?php echo i8ln("Use Areas"); ?>
               </label>
               <label class="btn btn-secondary mr-2">
                   <input type="radio" name="use_areas" id="use_areas" value="distance" <?php echo $distance_checked; ?> onclick="areas_add()"><?php echo i8ln("Set Distance"); ?>
               </label>
               </div>
           </div>
           <div class="input-group mt-2">
	       <input type="number" id='distance' name='distance' value='<?php echo $default_distance; ?>' min='0' max='<?php echo $_SESSION['maxDistance']; ?>' style="display:<?php echo $display; ?>"
                   class="form-control text-center">
               <div class="input-group-append" id="distance_label" style="display:none;">
                   <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
               </div>
           </div>
       </div>
   </div>
<?php } else if ( @$disable_location <> "True" && @$disable_areas == "True" ) { ?>
   <div class="form-row align-items-center">
       <div class="col-sm-12 my-1">
           <div class="input-group">
               <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
               <label class="btn btn-secondary mr-2">
                   <input type="radio" name="use_areas" id="use_areas" value="distance" checked><?php echo i8ln("Set Distance"); ?>
               </label>
               </div>
           </div>
           <div class="input-group mt-2">
               <input type="number" id='distance' name='distance' value='<?php echo $default_distance; ?>' min='0' max='<?php echo $_SESSION['maxDistance']; ?>'
                   class="form-control text-center">
               <div class="input-group-append" id="distance_label">
                   <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
               </div>
           </div>
       </div>
   </div>
<?php } else { ?>
      <input type="hidden" id='distance' name='distance' value='0'>
<?php } ?>
   
