
<?php

$sql = "select count(*) count FROM monsters WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_mon_tracked = $row['count']; }

$sql = "select count(*) count FROM raid WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_raid_tracked = $row['count']; }

$sql = "select count(*) count FROM egg WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_egg_tracked = $row['count']; }

$sql = "select count(*) count FROM quest WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_quest_tracked = $row['count']; }

$sql = "select count(*) count FROM invasion WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_invasion_tracked = $row['count']; }

$sql = "select count(*) count FROM lures WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_lure_tracked = $row['count']; }

$sql = "select count(*) count FROM nests WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_nests_tracked = $row['count']; }

$sql = "select count(*) count FROM gym WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_gyms_tracked = $row['count']; }


$num_areas=0;
$areas = explode(",", $area_set);
foreach ($areas as $key => $area) {
	$num_areas++;
}


?>

                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Content Row -->
                        <div class="row">

			    <!-- Card -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                                <div class="card shadow h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 text-gray-800 text-center mt-2" style="font-size:90%;">

						<?php if (@$disable_location <> "True") { ?>

                                                <?php if ($latitude == "0.0000000000" && $longitude == "0.0000000000") { ?>
                                                    <div class="alert alert-warning w-80 m-3" role="alert">
                                                        <?php echo i8ln("Your location is not set. Distance settings won't be taken into account."); ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="alert alert-success w-80 m-3" role="alert">
                                                    <?php echo i8ln("Your Location is set to"); ?><br>
                                                <?php if (@$disable_nominatim <> "True") {
                                                       $address=get_address($latitude, $longitude);
                                                       echo "<b>".$address."</b><br>";
                                                } ?>
                                                    <?php echo "[ ".round($latitude, 4); ?>,
						    <?php echo round($longitude, 4)." ]"; ?>
                                                </div>
						<?php } ?>

						<?php } ?>

                                                <?php if ($area == "[]" && @$disable_areas <> "True") { ?>
                                                <div class="alert alert-warning w-80 m-3" role="alert">
                                                    <?php echo i8ln("You have not set any area yet!"); ?>
                                                </div>
						<?php } else if ( @$disable_areas <> "True" ) { ?>
                                                    <a href="?type=display&page=area"><b>
                                                    <div class="alert alert-success w-80 m-3" role="alert">
						    <?php echo i8ln("You have configured"); ?>
						    <?php echo $num_areas; ?>
						    <?php echo i8ln("area(s)"); ?>
                                                    </b></a>
                                                    <br>
                                                    </div>
                                                <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
			    </div>

			    <!-- Card -->
                            <?php if (@$disable_mons <> "True") { ?>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=pokemon">
                                <div class="card border-top-dark shadow h-100 py-2">
				    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-0">
						    <img src="img/nav/mons.png" style="width:50px;height:50px;" class="mb-2"><br>
						    <?php echo $num_mon_tracked; ?><br><?php echo i8ln("Monsters"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
				</div>
                                </a>
			    </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_raids <> "True") { ?>
			    <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=raid">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-0">
						    <img src="img/nav/raid.svg" style="width:50px;height:50px;filter: brightness(40%);" class="mb-2"><br>
						    <?php echo $num_raid_tracked; ?><br><?php echo i8ln("Raids"); ?><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
				</div>
                                </a>
                            </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_raids <> "True") { ?>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=raid">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-0">
                                                    <img src="img/nav/egg.png" style="width:50px;height:50px;" class="mb-2"><br>
                                                    <?php echo $num_egg_tracked; ?><br><?php echo i8ln("Eggs"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_quests <> "True") { ?>
			    <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=quest">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-0">
                                                    <img src="img/nav/quest.png" style="width:50px;height:50px;" class="mb-2"><br>
						    <?php echo $num_quest_tracked; ?><br><?php echo i8ln("Quests"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
				</div>
                                </a>
                            </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_invasions <> "True") { ?>
			    <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=invasion">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <img src="img/nav/invasion.png" style="width:50px;height:50px;" class="mb-2"><br>
						    <?php echo $num_invasion_tracked; ?><br><?php echo i8ln("Invasions"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
				</div>
                                </a>
                            </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_lures <> "True") { ?>
			    <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=lure">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <img src="img/nav/lure.png" style="width:50px;height:50px;" class="mb-2"><br>
						    <?php echo $num_lure_tracked; ?><br><?php echo i8ln("Lures"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
				</div>
                                </a>
                            </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_nests <> "True") { ?>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=nest">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <img src="img/nav/nest.png" style="width:50px;height:50px;" class="mb-2"><br>
                                                    <?php echo $num_nests_tracked; ?><br><?php echo i8ln("Nests"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php } ?>

                            <!-- Card -->
                            <?php if (@$disable_gyms <> "True") { ?>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-4">
                                <a href="?type=display&page=gym">
                                <div class="card border-top-dark shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mt-2">
                                                    <img src="<?php echo "$uicons_gym/gym/0.png?"; ?>" style="width:50px;height:50px;" class="mb-2"><br>
                                                    <?php echo $num_gyms_tracked; ?><br><?php echo i8ln("Gyms"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php } ?>


                    </div>



