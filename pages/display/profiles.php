
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


                                   <a href="#" class="btn btn-danger btn-icon-split mr-2 mt-2 w-100" data-toggle="modal" data-target="#DeleteProfile">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text w-100"><?php echo i8ln("DELETE"); ?></span>
                                   </a>

                                   <!--
                                   <a href="#" class="btn btn-info btn-icon-split mr-2 mt-2 w-100" data-toggle="modal" data-target="#ScheduleProfile">
                                      <span class="icon text-white-100"><i class="fas fa-clock"></i></span>
                                      <span class="text w-100"><?php echo i8ln("SCHEDULE"); ?></span>
				   </a>
                                   -->

                                   <a href="#" class="btn btn-success btn-icon-split mr-2 mt-2 w-100" data-toggle="modal" data-target="#AddProfile">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text w-100"><?php echo i8ln("ADD"); ?></span>
                                   </a>

                                   <a href="#" class="btn btn-success btn-icon-split mr-2 mt-2 w-100" data-toggle="modal" data-target="#RenameProfile">
                                      <span class="icon text-white-100"><i class="fas fa-edit"></i></span>
                                      <span class="text w-100"><?php echo i8ln("RENAME"); ?></span>
                                   </a>

                                   <?php if ( $_SESSION['number_of_profiles'] > 1 ) { ?>
                                   <a href="#" class="btn btn-primary btn-icon-split mr-2 mt-2 w-100" data-toggle="modal" data-target="#SwitchProfile">
                                      <span class="icon text-white-100"><i class="fas fa-random"></i></span>
                                      <span class="text w-100"><?php echo i8ln("SWITCH"); ?></span>
                                   </a>
                                   <?php } ?>

		   		 </div>
		              </div>
                            </div>
                        </div>
		    </div>
                    <?php } ?>

                    <!-- PROFILE EDIT MODAL -->
                    <?php include "./modal/edit_profile_modal.php"; ?>

