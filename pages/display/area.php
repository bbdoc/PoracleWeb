
                    <!-- LOCATION Card -->
                    <?php if (@$disable_location <> "True" ) { ?>
                    <div class="col-xl-12 col-md-12">
                        <!-- Areas & Locations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row d-flex justify-content-between align-items-center pl-3 pr-3">
				    <?php if (@$disable_location <> "True") { ?>

					<a href="#" class="btn btn-danger btn-icon-split mr-2 mt-1 w-100" data-toggle="modal" data-target="#DeleteLocationModal">
                                           <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                           <span class="text w-100"><?php echo i8ln("DELETE"); ?> <?php echo i8ln("LOCATION"); ?></span>
	    			        </a>

                                        <?php
                                           if( isset($_SERVER['HTTPS'] ) ) { $site_is_https = "True"; }
                                           if( isset($site_is_https) && $site_is_https == "True") {
                                        ?>
                                        <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-1 w-100" onclick="getLocation()">
                                           <span class="icon text-white-100"><i class="fas fa-map-marker-alt"></i></span>
                                           <span class="text w-100"><?php echo i8ln("SET TO GPS LOCATION"); ?></span>
				        </a>
				        <?php } ?>

                                        <?php if (@$disable_nominatim <> "True") { ?>
                                        <a href="#" class="btn btn-success btn-icon-split mr-2 mt-1 w-100" data-toggle="modal" data-target="#locationModal">
                                           <span class="icon text-white-100"><i class="fas fa-edit"></i></span>
                                           <span class="text w-100"><?php echo i8ln("EDIT"); ?> <?php echo i8ln("LOCATION"); ?></span>
                                        </a>
				        <?php } ?>

                                    <?php } ?>

                                </div>
			    </div>

                            <div class="card-body">
                                <?php if (@$disable_location <> "True") { ?>
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="row">

                                            <div id="position_error_div" style="display:none;"
                                                class="text-center w-100 m-3">
                                                <div id="PERMISSION_DENIED" style="display:none;"
                                                    class="alert alert-danger" role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("User denied the request for Geolocation"); ?>.
                                                </div>
                                                <div id="POSITION_UNAVAILABLE" style="display:none;"
                                                    class="alert alert-danger" role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("Location information is unavailable"); ?>.
                                                </div>
                                                <div id="TIMEOUT" style="display:none;" class="alert alert-danger"
                                                    role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("The request to get user location timed out"); ?>.
                                                </div>
                                                <div id="UNKNOWN_ERROR" style="display:none;" class="alert alert-danger"
                                                    role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("An unknown error occurred"); ?>.
                                                </div>
                                                <div id="NOT_SUPPORTED" style="display:none;" class="alert alert-danger"
                                                    role="alert">
                                                    <b><?php echo i8ln("Could not set Location"); ?></b>.<br>
                                                    <?php echo i8ln("Geolocation is not supported by this browser"); ?>.
                                                </div>
                                            </div>


                                            <?php
                                            if ($latitude == "0.0000000000" && $longitude == "0.0000000000") {
                                            ?>
                                                <div class="alert alert-warning w-100 m-3" role="alert">
                                                    <?php echo i8ln("Your location is not set. Distance settings won't be taken into account."); ?>
                                                </div>
                                            <?php
					                        } else {
                                                ?>
					    <div class="alert alert-success w-100 m-3" role="alert">
						<?php echo i8ln("Your Location is set to"); ?><br>
                                                <?php 
					        if (@$disable_nominatim <> "True") { 
                                                   $address=get_address($latitude, $longitude); 
						   echo "<b>".$address."</b><br>"; 
						} 
                                                ?>
                                                <?php echo "[ ".round($latitude, 4); ?>,
                                                <?php echo round($longitude, 4)." ]"; ?>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- DELETE LOCATION Modal -->
                    <div class="modal fade" id="DeleteLocationModal" tabindex="-1" role="dialog"
                        aria-labelledby="DeleteLocationLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="DeleteLocationModalTitle">
                                        <?php echo i8ln("Delete Location and Distance Settings"); ?>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
				    <?php echo i8ln("This will delete your location AND all your distance settings"); ?><br>
                                    <?php echo i8ln("Are you sure?"); ?>
                                </div>
                                <div class="modal-footer">
                                    <a href="./actions/set_location.php?action=delete"
                                        class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT AREAS Modal -->
                    <div class="modal fade" id="areasModal" tabindex="-1" role="dialog"
                        aria-labelledby="areasModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php include "./modal/areas_modal.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT LOCATION Modal -->
                    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog"
                        aria-labelledby="locationModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php include "./modal/location_modal.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>



                    <?php if (@$disable_areas <> "True" ) { ?>
                    <div class="col-xl-12 col-md-12">
                        <!-- Areas -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row d-flex align-items-center pl-3 pr-3">

                                    <?php if (@$disable_areas <> "True") { ?>

                                    <a href="#" class="btn btn-success btn-icon-split mr-2 mt-1 w-100" data-toggle="modal" data-target="#areasModal">
                                       <span class="icon text-white-100"><i class="fas fa-edit"></i></span>
                                       <span class="text w-100"><?php echo i8ln("EDIT"); ?> <?php echo i8ln("AREAS"); ?></span>
                                    </a>

                                    <?php } ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <?php if (@$disable_areas <> "True") { ?>
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="row mr-3 ml-3">

                                            <?php
                                            if ($area == "[]") {
                                            ?>
                                            <div class="alert alert-warning w-100 m-3" role="alert">
                                                <?php echo i8ln("You have not set any area yet!"); ?>
                                            </div>
                                            <?php
                                                $areas = "";
                                            } else {
                                                $areas = explode(",", $area_set);

                                                foreach ($areas as $key => $area) {
                                                    $area = str_replace('"', '', $area);
                                                    $area = str_replace('[', '', $area);
                                                    $area = str_replace(']', '', $area);
                                                ?>
                                            <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 text-center">
                                                <div class="card bg-darkgrey text-white shadow mb-2">
                                                    <div class="card-body-areas">
                                                        <?php echo strtoupper($area); ?>
						    </div>
						    <?php if (file_exists(".cache/geo_".strtoupper($area).".png")) { ?>
						       <img src=".cache/geo_<?php echo strtoupper($area); ?>.png" style="width:100%; max-width=100px;"></img>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                </div>

