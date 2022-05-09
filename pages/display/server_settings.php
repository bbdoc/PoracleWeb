<?php

if (!isset($_SESSION['admin_id'])) {
        header("Location: $redirect_url");
        exit();
}

?>

            <form action='./actions/server_settings.php' method='POST'>


                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <?php

                        // Check if DB Settings exist 

                        $sql = "SHOW TABLES LIKE 'pweb_settings'";
                        $result = $conn->query($sql);
			if ($result->num_rows == 0) {
                                echo "<div class='alert alert-danger fade show' role='alert' style='padding:10px; margin:3px;'>";
                                echo i8ln("No Settings Found in DB. Settings have been pulled from config.php").".<br>";
                                echo i8ln("Please check your settings and Save to migrate to DB").". ";
                                echo "</div><br>";
			}
			else 
			{

                           // Check for DB Settings still in config.php

                           $sql = "select * FROM pweb_settings";
                           $result = $conn->query($sql);
           
	      		   $duplicates=array();
			   while ($row = $result->fetch_assoc()) { 

				   $lines = file("./config.php");
				   foreach ($lines as $lineNumber => $line) {
					   if (strpos($line, $row['setting']) !== false) {
						   $setting_value = strstr($line, '"');
						   $setting_value =  substr($setting_value, 1, strrpos ($setting_value, '"') - 1);
						   if ( $setting_value <> "" && strpos($line, "#") !== 0  && strpos($line, "//") !== 0 ) {
							   array_push($duplicates,$row['setting']);
						   }
					   }
				   }

                           }   

	   		   if ( count($duplicates) > 0 ) {
				echo "<div class='alert alert-danger fade show' role='alert' style='padding:10px; margin:3px;'>";
				echo i8ln("Following Settings have been migrated and should be removed from config.php").". ";
				echo "<code><center>".implode(" | ",$duplicates)."</center></code>";
				echo "</div><br>";
			   }

			}

                        ?>

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Server Status"); ?></h1>
                            </div>
                        </div>

                        <?php 

                        // Check Connection to Poracle DB

                        $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $dbname);
                        if ($conn->connect_errno) {
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Unable to Connect to Poracle DB")."</div>";
                           exit();
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Successfully Connected to Poracle DB")."</div>";
                        } 

                        // Check Connection to Scanner DB

                        $conn = new mysqli($scan_dbhost.":".$scan_dbport, $scan_dbuser, $scan_dbpass, $scan_dbname);
                        if ($conn->connect_errno) {
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("Unable to Connect to Scanner DB")."</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("Successfully Connected to Scanner DB")."</div>";
			}

                        // Check Connection to API

                        $opts = array( 'http'=>array( 'method'=>"GET", 'header'=>"Accept-language: en\r\n" .  "X-Poracle-Secret: $api_secret\r\n"));
			$context = stream_context_create($opts);
			$api = @file_get_contents("$api_address/api/config/poracleWeb", false, $context);
			$api_result = json_decode($api, true); 

                        if ( $api_result['status']=="ok" ) {
				$_SESSION['poracleVersion'] = $api_result['version'];
			}

                        if (!$api)
			{
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Unable to Connect to Poracle API")."</div>";
			} else if ( $api_result['status'] <> "ok" ) { 
			   echo "<div class='alert alert-danger fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("API");
			   echo " ".$api_result['status']." | ".$api_result['reason']."</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding:3px; margin:3px;'>".i8ln("Successfully Connected to Poracle API")."</div>";
                        }

			// Get Current Working Branch

                        $stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);
                        $firstLine = $stringfromfile[0]; //get the string from the array
                        $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
			$branchname = rtrim($explodedstring[2]); //get the one that is always the branch name

			// Set Main to Poracle JS master & Set unknown branches to PoracleJS develop
			
			if ( $branchname == "main" ) { $branchname = "master"; }
			else if ( $branchname <> "develop" ) { $branchname = "develop"; }

			// Get Latest Version for Current Branch

			$file = file_get_contents("https://raw.githubusercontent.com/KartulUdus/PoracleJS/".$branchname."/package.json");
			$obj = json_decode($file);
			$PJSversion = $obj->version;

                        // Check PoracleJS Version (if API is ok)

			if ( $api_result['status'] == "ok" ) {

                           if ( version_compare($_SESSION['poracleVersion'], $min_poracle_version) < 0 ) {
                              echo "<div class='alert alert-danger fade show' role='alert' style='padding: 3px; margin:3px;'>";
			      echo i8ln("Current PoracleJS Version")." ".$_SESSION['poracleVersion']."<br>";
			      echo i8ln("Latest").": ".$PJSversion." (branch: ".$branchname.")<br>";
                              echo i8ln("Required Version").": ".$min_poracle_version."<br>";
			      echo i8ln("Please Update PoracleJS")."</div>";
			      echo "</div>";
		   	   } else if ( version_compare($_SESSION['poracleVersion'], $PJSversion) < 0 ) {
			      echo "<div class='alert alert-warning fade show' role='alert' style='padding: 3px; margin:3px;'>";
			      echo i8ln("Current PoracleJS Version").": ".$_SESSION['poracleVersion']."<br>";
			      echo i8ln("Latest").": ".$PJSversion." (branch: ".$branchname.")<br>";
			      echo "</div>";
                           } else {
			      echo "<div class='alert alert-success fade show' role='alert' style='padding: 3px; margin:3px;'>";
			      echo i8ln("Current PoracleJS Version").": ".$_SESSION['poracleVersion']."<br>";
			      echo i8ln("Latest").": ".$PJSversion." (branch: ".$branchname.")<br>";
			      echo i8ln("Required Version").": ".$min_poracle_version;
			      echo "</div>";
			   }

			}

                        // Check Cache Folder

			if (!file_exists("./.cache"))
			{
			   echo "<div class='alert alert-warning fade show' role='alert' style='padding: 3px; margin:3px;'>";
			   echo i8ln("No Cache Folder found")."<br>";
			   echo i8ln("To activate cache please perform following actions from your PoracleWeb root folder").":<br>";
			   echo "<code>mkdir .cache<br>chown www-data:www-data .cache<br>chmod 744 .cache</code>";
			   echo "</div>";
			} else if (!is_writable("./.cache")) {
                           echo "<div class='alert alert-warning fade show' role='alert' style='padding: 3px; margin:3px;'>";
                           echo i8ln("Cannot Write to Cache Folder")."<br>";
                           echo i8ln("To activate cache please perform following actions from your PoracleWeb root folder").":<br>";
                           echo "<code>chown www-data:www-data .cache<br>chmod 744 .cache</code>";
                           echo "</div>";
                        } else {
                           echo "<div class='alert alert-success fade show' role='alert' style='padding: 3px; margin:3px;'>".i8ln("Cache Folder found. Cache Active")."</div>";
                        }

			// Check if Areas and Locations are both disabled
			
                        if ( $disable_areas == 'True' && $disable_location == 'True' ) {
                           echo "<div class='alert alert-danger fade show' role='alert' style='padding: 3px; margin:3px;'>";
                           echo i8ln("Both Areas & Locations are Disabled").".<br>";
                           echo i8ln("You won't be able to set any working alarms").".<br>";
                           echo "</div>";
                        }


                        ?>

                        <br>
                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Languages"); ?></h1>
                            </div>
                        </div>

                        <div class='searchmons text-center' id='dvLanguageList'>

			   <ul>
                              <?php
                                 $allowed_languages = explode(",", $allowed_languages);
                                 $all_languages = "en,fr,nl,de,es,pt,pl,da,br,se,it";
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
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Scanner DB"); ?></h1>
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
                                    <input type='password' id='scan_dbpass' name='scan_dbpass' class="form-control text-center" placeholder="********">
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


                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center mt-3">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("PogoInfo JSON"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">

                            <?php if (!isset($source_raid_bosses)) { $source_raid_bosses = "DB"; }  ?>
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text" style="width:230px;">&nbsp;&nbsp;<?php echo i8ln("Get Raid Bosses from"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="source_raid_bosses" id="source_raid_bosses" value="DB"
                                            <?php if ($source_raid_bosses == "DB") { echo "checked"; }  ?>>
                                        <?php echo i8ln("DB"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="source_raid_bosses" id="source_raid_bosses" value="JSON"
                                            <?php if ($source_raid_bosses == "JSON") { echo "checked"; }  ?>>
                                        <?php echo i8ln("JSON"); ?>
                                    </label>
				</div>
			    </div>

                        </div>

                    </div>

                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Poracle API"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("API Address"); ?>
                                        </div>
                                    </div>
				    <input type='text' id='api_address' name='api_address' class="form-control text-center" placeholder="http://127.0.0.1:4201" 
                                           value="<?php echo $api_address; ?>">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("API Secret"); ?>
                                        </div>
                                    </div>
                                    <input type='password' id='api_secret' name='api_secret' class="form-control text-center" placeholder="********">
                                </div>
                            </div>
			 </div>

                    </div>

                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Telegram | Discord"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">
			    <div class="col-sm-12 my-1">

                                <?php
				    if ( $_SESSION['type'] != "telegram:user" ) 
				    { 
					    $disabled = "disabled"; 
					    $msg = "<div class='alert alert-info fade show' role='alert' style='padding:5px; margin:3px;'>";
					    $msg .= i8ln("Connect as a Telegram Admin to disable Discord Login");
					    $msg .= "</div><br>";
				    } else 
				    { 
					    $disabled = "";
				    }
                                ?>

				<?php echo @$msg; ?>

				<div class="mb-1">

				<?php if ( $_SESSION['type'] == "telegram:user" ) { ?>
				   <input type="hidden" name="enable_discord" id="enable_discord" value="off">
				<?php } ?>

				<input type="checkbox" <?php echo $disabled; ?> name="enable_discord" id="enable_discord" <?php 
                                if (@$enable_discord <> "False") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Enable Discord Login ?"); ?>
				</div>

                                <div class="mb-1">
                                <input type="hidden" name="enable_telegram" id="enable_telegram" value="off">
                                <input type="checkbox" name="enable_telegram" id="enable_telegram" <?php
                                if (@$enable_telegram == "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
				&nbsp;&nbsp;<?php echo i8ln("Enable Telegram Login ?"); ?>
                                </div>
				     
				<div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:120px;">
                                            &nbsp;&nbsp;<?php echo i8ln("BOT Name"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='telegram_bot' name='telegram_bot' class="form-control text-center" value="<?php echo @$telegram_bot; ?>">
				</div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:120px;">
                                            &nbsp;&nbsp;<?php echo i8ln("BOT Token"); ?>
                                        </div>
                                    </div>
                                    <input type='password' id='telegram_bot_token' name='telegram_bot_token' class="form-control text-center" placeholder="********">
                                </div>

                            </div>
                         </div>

                    </div>

                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Icon Repository"); ?></h1>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

			<?php foreach ($uicons_repos as $repo_name => $repo_url){ ?>
			
			    <!-- Card -->

			    <?php if ($uicons_pkmn == $repo_url)   { $checked_pkmn="checked";} else { $checked_pkmn="";} ?>
			    <?php if ($uicons_raid == $repo_url)   { $checked_raid="checked";} else { $checked_raid="";} ?>
			    <?php if ($uicons_gym == $repo_url)    { $checked_gym="checked";} else { $checked_gym="";} ?>
			    <?php if ($uicons_reward == $repo_url) { $checked_reward="checked";} else { $checked_reward="";} ?>

			    <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-2">
                                <div class="card shadow h-100 py-25">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
						<div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
						    <center><font size=2><?php echo $repo_name; ?></font></center>
                                                    <hr>

						    <?php $PkmnImg = "$repo_url/pokemon/1.png"; ?>
						    <?php $EggImg = "$repo_url/raid/egg/1.png"; ?>
						    <?php $MegaImg = "$repo_url/reward/mega_resource/3.png"; ?>
						    <?php $GymImg = "$repo_url/gym/2.png"; ?>

						    <?php if (@getimagesize($PkmnImg)) { ?>
                                                       <input type="radio" id="uicons_repo_pkmn_<?php echo $repo_url; ?>" name="uicons_pkmn"
                                                              value="<?php echo $repo_url;?>" <?php echo $checked_pkmn;?>>
                                                       <label for='uicons_repo_pkmn_<?php echo $repo_url; ?>'>
						       <img loading=lazy width=50 src='<?php echo $PkmnImg; ?>'>
						       </label>
                                                    <?php } ?>

						    <?php if (@getimagesize($EggImg)) { ?>
                                                       <input type="radio" id="uicons_repo_raid_<?php echo $repo_url; ?>" name="uicons_raid"
                                                              value="<?php echo $repo_url;?>" <?php echo $checked_raid;?>>
                                                       <label for='uicons_repo_raid_<?php echo $repo_url; ?>'>
						       <img loading=lazy width=50 src='<?php echo $EggImg; ?>'><br>
                                                       </label>
                                                    <?php } ?>


						    <?php if (@getimagesize($GymImg)) { ?>
                                                       <input type="radio" id="uicons_repo_gym_<?php echo $repo_url; ?>" name="uicons_gym"
                                                              value="<?php echo $repo_url;?>" <?php echo $checked_gym;?>>
                                                       <label for='uicons_repo_gym_<?php echo $repo_url; ?>'>
				   		       <img loading=lazy width=50 src='<?php echo $GymImg; ?>'>
				   	   	       </label>
                                                    <?php } ?>


						    <?php if (@getimagesize($MegaImg)) { ?>
                                                       <input type="radio" id="uicons_repo_reward_<?php echo $repo_url; ?>" name="uicons_reward"
                                                              value="<?php echo $repo_url;?>" <?php echo $checked_reward;?>>
                                                       <label for='uicons_repo_reward_<?php echo $repo_url; ?>'>
						       <img loading=lazy width=50 src='<?php echo $MegaImg; ?>'>
                                                       </label>
                                                    <?php } ?>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
			    </div>

                        <?php } ?>

                    </div>


















                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Enable Menu Items"); ?></h1>
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
                                                 if (@$disable_mons <> "True") {
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
                                                if (@$disable_raids <> "True") {
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
                                                if (@$disable_quests <> "True") {
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
                                                if (@$disable_invasions <> "True") {
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
                                                if (@$disable_lures <> "True") {
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
                                                if (@$disable_nests <> "True") {
                                                    echo "checked";
                                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if ($disable_gyms == "True") { $border = "border-danger"; } else { $border = "border-success";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                                <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-">
                                                    <?php echo i8ln("Gyms"); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                <input type="hidden" name="disable_gyms" id="disable_gyms" value="off">
                                                <input type="checkbox" name="disable_gyms" id="disable_gyms" <?php
                                                if (@$disable_gyms <> "True") {
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
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Enable Options"); ?></h1>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <!-- Card -->
                            <?php if (@$disable_profiles <> "True") { $border = "border-success"; } else { $border = "border-danger";} ?>
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
                                                 if (@$disable_profiles <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if (@$disable_areas <> "True") { $border = "border-success"; } else { $border = "border-danger";} ?>
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
                                                 if (@$disable_areas <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if (@$disable_location <> "True") { $border = "border-success"; } else { $border = "border-danger";} ?>
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
                                                 if (@$disable_location <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if (@$disable_nominatim <> "True") { $border = "border-success"; } else { $border = "border-danger";} ?>
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
                                                 if (@$disable_nominatim <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if (@$disable_geomap <> "True") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Geo Maps")."<font size=1><br>".i8ln("on display screen")."</font>"; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
					    <div class="row">
                                                 <input type="hidden" name="disable_geomap" id="disable_geomap" value="off">
                                                 <input type="checkbox" name="disable_geomap" id="disable_geomap" <?php
                                                 if (@$disable_geomap <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php if (@$disable_geomap_select <> "True") { $border = "border-success"; } else { $border = "border-danger";} ?>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-6 mb-4">
                            <div class="card <?php echo $border; ?> shadow h-100 py-2">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-center mb-1">
                                                    <?php echo i8ln("Geo Maps")."<font size=1><br>".i8ln("on select screen")."</font>"; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="row">
                                                 <input type="hidden" name="disable_geomap_select" id="disable_geomap_select" value="off">
                                                 <input type="checkbox" name="disable_geomap_select" id="disable_geomap_select" <?php
                                                 if (@$disable_geomap_select <> "True") {
                                                    echo "checked";
                                                 } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                                    data-size="sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


		    </div>

                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Additional Settings"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">

                                <div class="mb-1">
                                <input type="hidden" name="debug" id="debug" value="off">
                                <input type="checkbox" name="debug" id="debug" <?php
                                if (@$debug == "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Enable Debug Mode"); ?>
                                </div>

                                <div class="mb-1">
                                <input type="hidden" name="enable_templates" id="enable_templates" value="off">
                                <input type="checkbox" name="enable_templates" id="enable_templates" <?php
                                if (@$enable_templates == "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Enable Templates"); ?>
				</div>

                                <div class="mb-1">
                                <input type="hidden" name="enable_roles" id="enable_roles" value="off">
                                <input type="checkbox" name="enable_roles" id="enable_roles" <?php
                                if (@$enable_roles == "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Enable Roles"); ?>
                                </div>


                                <div class="mb-1">
                                <input type="hidden" name="enable_admin_dis" id="enable_admin_dis" value="off">
                                <input type="checkbox" name="enable_admin_dis" id="enable_admin_dis" <?php
                                if (@$enable_admin_dis <> "False") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Allow 'Admin Disabled' Users"); ?>
                                </div>

                                <div class="mb-1">
                                <input type="hidden" name="admin_disable_userlist" id="admin_disable_userlist" value="off">
                                <input type="checkbox" name="admin_disable_userlist" id="admin_disable_userlist" <?php
                                if (@$admin_disable_userlist <> "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Enable User List in Admin Tools"); ?>
				</div>

                                <div class="mb-1">
                                <input type="hidden" name="admin_channel_id" id="admin_channel_id" value="off">
                                <input type="checkbox" name="admin_channel_id" id="admin_channel_id" <?php
                                if (@$admin_channel_id == "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Display Channel ID in Admin Tools"); ?>
                                </div>

                                <div class="mb-1">
                                <input type="hidden" name="site_is_https" id="site_is_https" value="off">
                                <input type="checkbox" name="site_is_https" id="site_is_https" <?php
                                if (@$site_is_https == "True") { echo "checked"; } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                &nbsp;&nbsp;<?php echo i8ln("Site is running HTTPS"); ?>
                                </div>

                                <?php if (!isset($custom_title)) { $custom_title="PoracleWeb"; } ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:170px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Custom Title"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='custom_title' name='custom_title' class="form-control text-center" value="<?php echo $custom_title; ?>">
                                </div>

				<?php if (!isset($register_command)) { $register_command="!poracle"; } ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:170px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Register Command"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='register_command' name='register_command' class="form-control text-center" value="<?php echo $register_command; ?>">
				</div>

				<?php if (!isset($location_command)) { $location_command="!location"; } ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:170px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Location Command"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='location_command' name='location_command' class="form-control text-center" value="<?php echo $location_command; ?>">
				</div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"  style="width:170px;">
                                            &nbsp;&nbsp;<?php echo i8ln("gAnalyticsId"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='gAnalyticsId' name='gAnalyticsId' class="form-control text-center" value="<?php echo @$gAnalyticsId; ?>">
				</div>

                            </div>
                         </div>

                    </div>

                    <br>
                    <div class="tab-pane fade active show" id="pills-lures" role="tabpanel" aria-labelledby="pills-lures-tab">

                        <!-- Page Heading -->
                        <div class="text-center">
                            <div class="breadcrumb justify-content-center">
                                <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Additional Pages"); ?></h1>
                            </div>
                        </div>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Paypal URL"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='paypalUrl' name='paypalUrl' class="form-control text-center" value="<?php echo @$paypalUrl; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Patreon URL"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='patreonUrl' name='patreonUrl' class="form-control text-center" value="<?php echo @$patreonUrl; ?>">
                                </div>
                            </div>
                        </div>

                        <center><br>
			<?php echo i8ln("Add a custom Page by giving its Name, URL and an icon"); ?>. 
			<?php echo i8ln("Chose Any Free Icon from"); ?> 
                        <br><a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a><br> e.g. <code>fas fa-globe-europe</code>
			</center>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Name"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='custom_page_name' name='custom_page_name' class="form-control text-center" value="<?php echo @$custom_page_name; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("URL"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='custom_page_url' name='custom_page_url' class="form-control text-center" value="<?php echo @$custom_page_url; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="width:130px;">
                                            &nbsp;&nbsp;<?php echo i8ln("Icon"); ?>
                                        </div>
                                    </div>
                                    <input type='text' id='custom_page_icon' name='custom_page_icon' class="form-control text-center" value="<?php echo @$custom_page_icon; ?>">
                                </div>
                            </div>
                        </div>




		    </div>

                <br>
                <div class="modal-footer">
                    <input type="hidden" id="type" name="action" value="profile_settings">
                    <button type="submit" name='update' value='Update' class="btn btn-primary">
                        <?php echo i8ln("Save changes"); ?>
                    </button>
                </div>
            </form>


