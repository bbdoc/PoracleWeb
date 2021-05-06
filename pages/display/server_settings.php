
<?php
include "../../header.php";
?>

            <form action='./actions/server_settings.php' method='POST'>


                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("SERVER STATUS"); ?></h1>
                            </div>
                        </div>

                        <?php

                        $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
                        if ($conn->connect_errno) {
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Unable to Connected to Poracle DB")."</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Successfully Connected to Poracle DB")."</div>";
                        } 

                        $conn = new mysqli($scan_dbhost.":".$scan_dbport, $scan_dbuser, $scan_dbpass, $scan_db);
                        if ($conn->connect_errno) {
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("Unable to Connected to Scanner DB")."</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("Successfully Connected to Scanner DB")."</div>";
			}

                        $opts = array( 'http'=>array( 'method'=>"GET", 'header'=>"Accept-language: en\r\n" .  "X-Poracle-Secret: $api_secret\r\n"));
                        $context = stream_context_create($opts);

                        if (!$api = file_get_contents("$api_address/api/config/poracleWeb", false, $context))
			{
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Unable to Connected to Poracle API ")."</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Successfully Connected to Poracle API")."</div>";
                        }

			if (!file_exists("./.cache")) 
			{
                           echo "<div class='alert alert-warning fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("No Cache Folder found. Cache Inactive")."</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("Cache Folder found. Cache Active")."</div>";
                        }


                        ?>

                        <br>
                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("LANGUAGES"); ?></h1>
                            </div>
                        </div>

                        <div class='searchmons text-center' id='dvLanguageList'>

			   <ul>
                              <?php
                                 $allowed_languages = explode(",", $allowed_languages);
                                 $all_languages = "fr,nl,de,es,pt,pl,da,br,se";
                                 $all_languages = explode(",", $all_languages);
				 foreach ($all_languages as &$language) { 
				    if (in_array($language, $allowed_languages)) { $checked="checked"; } else {$checked="";} 
                              ?>
			      <li><input type='checkbox' name='language_<?php echo $language; ?>' id='language_<?php echo $language; ?>' <?php echo $checked; ?> />
                                 <label for='language_<?php echo $language; ?>'><img loading=lazy src='img/<?php echo $language; ?>.png' style='margin-bottom:10px;' />
                       	      </li>
                              <?php } ?>
                           </ul>
                        </div>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("SCANNER DB"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text" style="width:130px;">&nbsp;&nbsp;<?php echo i8ln("DB Type"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="scan_dbtype" id="scan_dbtype" value="MAD"
                                            <?php if ($scan_dbtype == "MAD") { echo "checked"; }  ?>>
                                        <?php echo i8ln("MAD"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="scan_dbtype" id="scan_dbtype" value="RDM"
                                            <?php if ($scan_dbtype == "RDM") { echo "checked"; }  ?>>
                                        <?php echo i8ln("RDM"); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("DB Host"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='scan_dbhost' name='scan_dbhost' class="form-control text-center" value="<?php echo $scan_dbhost; ?>">
				</div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("DB Name"); ?>
                                        </div>
                                    </div>
				    <input type='text' id='scan_dbname' name='scan_dbname' class="form-control text-center" value="<?php echo $scan_dbname; ?>">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("DB User"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='scan_dbuser' name='scan_dbuser' class="form-control text-center" value="<?php echo $scan_dbuser; ?>">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Password"); ?>
                                        </div>
                                    </div>
                                    <input type='password' id='scan_dbpass' name='scan_dbpass' class="form-control text-center" value="<?php echo $scan_dbpass; ?>">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Port"); ?>
                                        </div>
                                    </div>
                                    <input type='number' id='scan_dbport' name='scan_dbport' class="form-control text-center" min='0' value="<?php echo $scan_dbport; ?>">
                                </div>
                            </div>

                        </div>

                    </div>

                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("PORACLE API"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("API IP/Host"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='api_address' name='api_address' class="form-control text-center" value="<?php echo $api_address; ?>">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("API Secret"); ?>
                                        </div>
                                    </div>
                                    <input type='password' id='api_secret' name='api_secret' class="form-control text-center" value="<?php echo $api_secret; ?>">
                                </div>
                            </div>
			 </div>

                    </div>
 
                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("ENABLE MENU ITEMS"); ?></h1>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

			    <!-- Card -->
			    <?php if ($disable_mons == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
			    <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
						    <?php echo i8ln("Monsters"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_mons" id="disable_mons" value="off">
                                                 <input type="checkbox" name="disable_mons" id="disable_mons" <?php
                                                 if ($disable_mons <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
			    <?php if ($disable_raids == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                                <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Raids"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="hidden" name="disable_raids" id="disable_raids" value="off">
                                                <input type="checkbox" name="disable_raids" id="disable_raids" <?php
                                                if ($disable_raids <> "True") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
			    <?php if ($disable_quests == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                                <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Quests"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="hidden" name="disable_quests" id="disable_quests" value="off">
                                                <input type="checkbox" name="disable_quests" id="disable_quests" <?php
                                                if ($disable_quests <> "True") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
			    <?php if ($disable_invasions == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                                <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Invasions"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="hidden" name="disable_invasions" id="disable_invasions" value="off">
                                                <input type="checkbox" name="disable_invasions" id="disable_invasions" <?php
                                                if ($disable_invasions <> "True") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
			    <?php if ($disable_lures == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                                <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Lures"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="hidden" name="disable_lures" id="disable_lures" value="off">
                                                <input type="checkbox" name="disable_lures" id="disable_lures" <?php
                                                if ($disable_lures <> "True") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Card -->
                            <?php if ($disable_nests == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                                <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-">
                                                    <?php echo i8ln("Nests"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                <input type="hidden" name="disable_nests" id="disable_nests" value="off">
                                                <input type="checkbox" name="disable_nests" id="disable_nests" <?php
                                                if ($disable_nests <> "True") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

		    </div>

                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("ENABLE OPTIONS"); ?></h1>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <!-- Card -->
                            <?php if ($all_mon_cleaned == "1") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Profiles"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_profiles" id="disable_profiles" value="off">
                                                 <input type="checkbox" name="disable_profiles" id="disable_profiles" <?php
                                                 if ($disable_profiles <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if ($all_mon_cleaned == "1") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Areas"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_areas" id="disable_areas" value="off">
                                                 <input type="checkbox" name="disable_areas" id="disable_areas" <?php
                                                 if ($disable_areas <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if ($all_mon_cleaned == "1") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Location"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_location" id="disable_locations" value="off">
                                                 <input type="checkbox" name="disable_location" id="disable_location" <?php
                                                 if ($disable_location <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if ($all_mon_cleaned == "1") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Nominatim"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_nominatim" id="disable_nominatim" value="off">
                                                 <input type="checkbox" name="disable_nominatim" id="disable_nominatim" <?php
                                                 if ($disable_nominatim <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if ($all_mon_cleaned == "1") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Geo Maps"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_geomap" id="disable_geomap" value="off">
                                                 <input type="checkbox" name="disable_geomap" id="disable_geomap" <?php
                                                 if ($disable_geomap <> "True") {
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


