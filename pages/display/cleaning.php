
<?php
include "../../header.php";
?>

            <form action='./actions/profile.php' method='POST'>

                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("ACTIVATE CLEANING ON ALL"); ?></h1>
                            </div>
                        </div>


                        <!-- Content Row -->
                        <div class="row">

                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
						    <?php echo i8ln("Monsters"); ?>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="checkbox" name="pokes_clean_toggle" id="pokes_clean_toggle" <?php
                                                 if ($all_mon_cleaned == "1") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln("Raid/Egg"); ?>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="checkbox" name="re_clean_toggle" id="re_clean_toggle" <?php
                                                if ($all_raid_cleaned == "1") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln("Quests"); ?>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="checkbox" name="quests_clean_toggle" id="quests_clean_toggle" <?php
                                                if ($all_quest_cleaned == "1") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln("Invasions"); ?>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="checkbox" name="invasions_clean_toggle" id="invasions_clean_toggle" <?php
                                                if ($all_invasion_cleaned == "1") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="card border-top-success shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <?php echo i8ln("Lures"); ?>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="checkbox" name="leures_clean_toggle" id="leures_clean_toggle" <?php
                                                if ($all_lures_cleaned == "1") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>

                <div class="modal-footer">
                    <input type="hidden" id="type" name="action" value="profile_settings">
                    <button type="submit" name='update' value='Update' class="btn btn-primary">
                        <?php echo i8ln("Save changes"); ?>
                    </button>
                </div>
            </form>


