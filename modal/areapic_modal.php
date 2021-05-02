
<!-- Modal -->
<div class="modal fade" id="areapic_<?php echo $area; ?>" tabindex="-1" role="dialog" aria-labelledby="areapic_<?php echo $area; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">

         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
             <div class="card bg-darkgrey text-white shadow mb-2">
                 <div class="card-body-areas">
	   	     <?php echo $area; ?>
                 </div>
                 <?php $area = str_replace(' ', '_', $area);  ?>
                 <?php if (file_exists(".cache/geo_".$area."_".$hash.".png") && $disable_geomap <> "True" ) { ?>
                    <img src=".cache/geo_<?php echo $area; ?>_<?php echo $hash; ?>.png" style="width:100%; max-width=100px;"
                         data-toggle="modal" data-target="#areapic_<?php echo $area; ?>"></img>
                 <?php } ?>
             </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>

      </div>
    </div>
  </div>
</div>
