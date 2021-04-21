
                <!-- Content Row -->
                <div class="row">

		    <!-- PROFILE Card -->
		    <?php if (@$disable_profiles <> "True") { ?>

                    <div class="col-xl-12 col-md-12">
			<!-- Current Profile -->

			<div class="card shadow mb-4">
                            <div class="card-header py-3">
				<div class="row d-flex justify-content-between align-items-center pl-3 pr-3">
				   <h6 class="m-0 font-weight-bold text-dark"><?php echo i8ln("Profile"); ?>: 
				   <strong><?php echo $_SESSION['profile_name']; ?></strong>
				   </h6>
				   <?php if ( $_SESSION['profile'] == $_SESSION['current_profile'] ) { ?>
				   <span class="badge badge-success badge-pill ml-2" style="padding:5px; padding-right:15px; padding-left:15px;">
				      <?php echo i8ln("Active"); ?>
                                   </span>
				   <?php } else { ?>
                                   <span class="badge badge-info badge-pill ml-2" style="padding:5px; padding-right:15px; padding-left:15px;">
                                      <?php echo i8ln("Not Active"); ?>
                                   </span>
                                   <?php } ?>
                               </div>
                            </div>

                            <div class="card-body">
			      <div class="row no-gutters align-items-center">
                                <div class="text-center w-100">
				   <button type="button" class="btn btn-danger btn-circle btn-md m-1" 
                                       data-toggle="modal" data-target="#DeleteProfile">
                                       <i class="fas fa-trash"></i>
				   </button>
                                   <!--
				   <button type="button" class="btn btn-info btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#ScheduleProfile">
                                       <i class="fas fa-clock"></i>
				   </button>
                                   -->
				   <button type="button" class="btn btn-success btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#AddProfile">
                                       <i class="fas fa-plus-circle"></i>
				   </button>
				   <button type="button" class="btn btn-success btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#RenameProfile">
                                       <i class="fas fa-edit"></i>
				   </button>
                                   <?php if ( $_SESSION['number_of_profiles'] > 1 ) { ?>
				   <button type="button" class="btn btn-success btn-circle btn-md m-1"  
                                       data-toggle="modal" data-target="#SwitchProfile">
                                       <i class="fas fa-random"></i>
				   </button>
                                   <?php } ?>

		   		 </div>
		              </div>
                            </div>
                        </div>
		    </div>
                    <?php } ?>

                    <!-- PROFILE EDIT MODAL -->
                    <?php include "./modal/edit_profile_modal.php"; ?>

