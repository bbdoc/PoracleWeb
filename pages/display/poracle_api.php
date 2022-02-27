
<?php

if (!isset($_SESSION['admin_id'])) {
        header("Location: $redirect_url");
        exit();
}

?>

<div class="tab-pane fade active show" role="tabpanel">

    <center><strong>
    <?php echo i8ln("Settings pulled from Poracle API"); ?><br>
    <?php echo i8ln("Update from Poracle config file"); ?>: local.json<hr>
    </strong></center> 

    <div class="text-center">
       <div class="breadcrumb justify-content-center">
          <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Server Settings"); ?></h1>
       </div>
    </div>

    <div class="form-row align-items-center">

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:230px;">
                        &nbsp;&nbsp;<?php echo i8ln("Server Default Locale"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['locale']; ?></div>
	    </div>
	</div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:230px;">
                        &nbsp;&nbsp;<?php echo i8ln("Everything Flag Permissions"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['everythingFlagPermissions']; ?></div>
            </div>
        </div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:230px;">
                        &nbsp;&nbsp;<?php echo i8ln("Default Template"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['defaultTemplateName']; ?></div>
            </div>
        </div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:230px;">
                        &nbsp;&nbsp;<?php echo i8ln("Max Distance"); ?>
                    </div>
                </div>
		<div class="form-control text-center">
		<?php if ( $_SESSION['maxDistance'] == 10726000 ) { echo i8ln("Unlimited")." (10.726 km)"; } else { echo $_SESSION['maxDistance']; } ?>
                </div>
            </div>
        </div>

    </div>

    <br>
    <div class="text-center">
       <div class="breadcrumb justify-content-center">
          <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Nominatim Settings"); ?></h1>
       </div>
    </div>

    <div class="form-row align-items-center">


        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:130px;">
                        &nbsp;&nbsp;<?php echo i8ln("Provider URL"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['providerURL']; ?></div>
            </div>
        </div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:130px;">
                        &nbsp;&nbsp;<?php echo i8ln("Static Key"); ?>
                    </div>
                </div>
		<div class="form-control text-center">
		<?php if ($_SESSION['staticKey'] <> "Your MapQuest or Google Key" ) { echo $_SESSION['staticKey']; } ?>
                </div>
            </div>
        </div>

    </div>

    <br>
    <div class="text-center">
       <div class="breadcrumb justify-content-center">
          <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("PvP Settings"); ?></h1>
       </div>
    </div>

    <div class="form-row align-items-center">

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:220px;">
                        &nbsp;&nbsp;<?php echo i8ln("PvP Max Allowed Rank"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['pvpFilterMaxRank']; ?></div>
            </div>
        </div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:220px;">
                        &nbsp;&nbsp;<?php echo i8ln("PvP Caps"); ?>
                    </div>
                </div>
		<div class="form-control text-center">
                    <?php 
                    foreach($_SESSION['pvpCaps'] as $key => $cap) {
		        echo "<span class='badge badge-dark' style='width:80px;'>".$cap."</span>";
		    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:220px;">
                        &nbsp;&nbsp;<?php echo i8ln("PvP Default Cap"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['defaultPvpCap']; ?></div>
            </div>
        </div>


        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:220px;">
                        &nbsp;&nbsp;<?php echo i8ln("PvP Little Min CP"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['pvpFilterLittleMinCP']; ?></div>
            </div>
        </div>

	<?php if ( $_SESSION['pvpLittleLeagueAllowed'] == "True" ) { ?>

        <div class='alert alert-success fade show w-100' role='alert' style='padding:5px; margin:3px;'>
        <?php echo i8ln("PvP datasource set to internal, Little Cup enabled"); ?>
        </div><br>

        <?php } else { ?>

        <div class='alert alert-danger fade show w-100' role='alert' style='padding:5px; margin:3px;'>
	<?php echo i8ln("Please set PvP datasource to internal to enable Little Cup"); ?>
        </div><br>

        <?php } ?>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:220px;">
                        &nbsp;&nbsp;<?php echo i8ln("PvP Great Min CP"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['pvpFilterGreatMinCP']; ?></div>
            </div>
        </div>

        <div class="col-sm-12 my-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" style="width:220px;">
                        &nbsp;&nbsp;<?php echo i8ln("PvP Ultra Min CP"); ?>
                    </div>
                </div>
                <div class="form-control text-center"><?php echo $_SESSION['pvpFilterUltraMinCP']; ?></div>
            </div>
        </div>

    </div>

    <br>
    <div class="text-center">
       <div class="breadcrumb justify-content-center">
          <h1 class="h3 mb-0 text-gray-800 "><?php echo i8ln("Poracle Admins"); ?></h1>
       </div>
    </div>

    <div class="form-row align-items-center">

        <div class="col-sm-12 my-1">
            <div class="input-group">
		<div class="form-control text-center" style="height:auto;">

                <?php

                foreach($_SESSION['poracle_admins'] as $key => $padmin) { 

                   $sql = "select type, name FROM humans where id = '$padmin'";
                   $result = $conn->query($sql);

		   while ($row = $result->fetch_assoc()) {
                      if ($row['type'] == "discord:user") { $color="primary"; } else if ($row['type'] == "telegram:user") { $color="info"; }
                      echo "<span class='badge badge-$color' style='width:100%;'>".$row['type']." | ".$padmin." | ".$row['name']."</span><br>";
                   }

		}

                ?>

                </div>
            </div>
        </div>

    </div>

</div>



