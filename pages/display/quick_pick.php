
<?php

   $sql = "select min(clean) clean FROM monsters WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $mon_cleaned = $row['clean'];
   }

   $sql = "select min(distance) distance FROM monsters WHERE id = '" . $_SESSION['id'] . "'";
   $result = $conn->query($sql);
   while ($row = $result->fetch_assoc()) {
       $mon_distance = $row['distance'];
   }


?>

                <!-- Content Row -->
                <div class="row">

                    <!-- QUICK PIC Card -->

		    <div class="col-xl-12 col-sm-12 col-12">

                        <div class="mb-3">
                            <center>
			    <?php echo i8ln("Click the <i class='fas fa-plus'></i> button to quickly add a tracking").".<br>"; ?>
			    <?php echo i8ln("Already tracked are shown in green")."."; ?>
                            </center>
		        </div>

                        <div class="card shadow mb-4">

                            <div class="card-header py-3">
                                  <strong><center><?php echo i8ln("Common Trackings"); ?></center></strong>
                            </div>

                            <div class="card-body mb-2">

                                <div class="row d-flex justify-content-between align-items-center pl-3 pr-3">

                                <?php
                                   
                                   $sql = "select uid FROM monsters WHERE min_iv = 100 AND pokemon_id = 0 AND id = '" . $_SESSION['id'] . "'";
                                   $result = $conn->query($sql);
				   if ( $result->num_rows > 0 ) { $found = 1; $style = "background:#1cc88a; color:white;"; } else { $found = ""; $style = ""; }
                                   
                                   while ($row = $result->fetch_assoc()) {
					   $uid = $row['uid'];
                                   } 
                                   
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
				   <div class="input-group-text mr-1 justify-content-center" style="width:80%; <?php echo $style; ?>"><?php echo i8ln("100% IV Pokemon"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("100% IV Pokemon"); ?>'>
                                   <input type='hidden' id='pokemon_id' name='pokemon_id' value='0'>
				   <input type='hidden' id='min_iv' name='min_iv' value='100'>
                                   <?php if ( $found == 1 ) { ?>
				   <input type='hidden' id='action' name='action' value='delete'>
				   <input type='hidden' id='uid' name='uid' value='<?php echo $uid; ?>'>
				   <button type='submit' class="btn btn-danger btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("DELETE"); ?></span>
                                   </button>
				   <?php } else { ?>
				   <input type='hidden' id='action' name='action' value='add'>
				   <button type='submit' class="btn btn-success btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("ADD"); ?></span>
                                   </button>
				   <?php } ?>
                                </div>
                                </form>

                                <?php
                                   $sql = "select uid FROM monsters WHERE pokemon_id = 0 AND pvp_ranking_league = 500 AND pvp_ranking_worst = 1 AND id = '" . $_SESSION['id'] . "'";
                                   $result = $conn->query($sql);
                                   if ( $result->num_rows > 0 ) { $found = 1; $style = "background:#1cc88a; color:white;"; } else { $found = ""; $style = ""; }

                                   while ($row = $result->fetch_assoc()) {
                                           $uid = $row['uid'];
                                   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="min-width:80%; <?php echo $style; ?>"><?php echo i8ln("PvP Little Rank 1"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("PvP Little Rank 1"); ?>'>
                                   <input type='hidden' id='pokemon_id' name='pokemon_id' value='0'>
                                   <input type='hidden' id='league' name='league' value='500'>
                                   <input type='hidden' id='pvp_ranking_worst' name='pvp_ranking_worst' value='1'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo $uid; ?>'>
                                   <button type='submit' class="btn btn-danger btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("DELETE"); ?></span>
                                   </button>
                                   <?php } else { ?>
                                   <input type='hidden' id='action' name='action' value='add'>
                                   <button type='submit' class="btn btn-success btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("ADD"); ?></span>
                                   </button>
                                   <?php } ?>
				</div>
                                </form>

                                <?php
                                   $sql = "select uid FROM monsters WHERE pokemon_id = 0 AND pvp_ranking_league = 1500 AND pvp_ranking_worst = 1 AND id = '" . $_SESSION['id'] . "'";
                                   $result = $conn->query($sql);
                                   if ( $result->num_rows > 0 ) { $found = 1; $style = "background:#1cc88a; color:white;"; } else { $found = ""; $style = ""; }

                                   while ($row = $result->fetch_assoc()) {
                                           $uid = $row['uid'];
                                   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="min-width:80%; <?php echo $style; ?>"><?php echo i8ln("PvP Great Rank 1"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("PvP Great Rank 1"); ?>'>
                                   <input type='hidden' id='pokemon_id' name='pokemon_id' value='0'>
                                   <input type='hidden' id='league' name='league' value='1500'>
                                   <input type='hidden' id='pvp_ranking_worst' name='pvp_ranking_worst' value='1'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo $uid; ?>'>
                                   <button type='submit' class="btn btn-danger btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("DELETE"); ?></span>
                                   </button>
                                   <?php } else { ?>
                                   <input type='hidden' id='action' name='action' value='add'>
                                   <button type='submit' class="btn btn-success btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("ADD"); ?></span>
                                   </button>
                                   <?php } ?>
                                </div>
                                </form>

                                <?php
                                   $sql = "select uid FROM monsters WHERE pokemon_id = 0 AND pvp_ranking_league = 2500 AND pvp_ranking_worst = 1 AND id = '" . $_SESSION['id'] . "'";
                                   $result = $conn->query($sql);
                                   if ( $result->num_rows > 0 ) { $found = 1; $style = "background:#1cc88a; color:white;"; } else { $found = ""; $style = ""; }

                                   while ($row = $result->fetch_assoc()) {
                                           $uid = $row['uid'];
                                   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="min-width:80%; <?php echo $style; ?>"><?php echo i8ln("PvP Ultra Rank 1"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("PvP Ultra Rank 1"); ?>'>
                                   <input type='hidden' id='pokemon_id' name='pokemon_id' value='0'>
                                   <input type='hidden' id='league' name='league' value='2500'>
                                   <input type='hidden' id='pvp_ranking_worst' name='pvp_ranking_worst' value='1'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo $uid; ?>'>
                                   <button type='submit' class="btn btn-danger btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("DELETE"); ?></span>
                                   </button>
                                   <?php } else { ?>
                                   <input type='hidden' id='action' name='action' value='add'>
                                   <button type='submit' class="btn btn-success btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("ADD"); ?></span>
                                   </button>
                                   <?php } ?>
                                </div>
                                </form>


                                </div>

                            </div>

                        </div>
                    </div>

		    <!-- QUICK PIC Card -->

		    <div class="col-xl-12 col-sm-12 col-12">

			<div class="card shadow mb-4">

			    <div class="card-header py-3">
                                  <strong><center><?php echo i8ln("Magikarps & Ratatas"); ?></center></strong>
			    </div>

			    <div class="card-body mb-2">

				<div class="row d-flex justify-content-between align-items-center pl-3 pr-3">

                                <?php
                                   $sql = "select uid FROM monsters WHERE pokemon_id = 129 AND min_weight = 13130 AND id = '" . $_SESSION['id'] . "'";
                                   $result = $conn->query($sql);
                                   if ( $result->num_rows > 0 ) { $found = 1; $style = "background:#1cc88a; color:white;"; } else { $found = ""; $style = ""; }

                                   while ($row = $result->fetch_assoc()) {
                                           $uid = $row['uid'];
                                   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1" style="width:40%;min-width:150px; <?php echo $style; ?>"><?php echo i8ln("Big Magikarps"); ?></div>
                                   <div class="input-group-prepend">
                                       <div class="input-group-text"><?php echo i8ln("IV Min"); ?></div>
				   </div>
				   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
				   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("Big Magikarps"); ?>'>
                                   <input type='hidden' id='pokemon_id' name='pokemon_id' value='129'>
                                   <input type='hidden' id='min_weight' name='min_weight' value='13130'>
				   <input type='number' id='min_iv' name='min_iv' size=1 placeholder='0' min='0' max='100' style="width:60px; text-align:center;">
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo $uid; ?>'>
                                   <button type='submit' class="btn btn-danger btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("DELETE"); ?></span>
                                   </button>
                                   <?php } else { ?>
                                   <input type='hidden' id='action' name='action' value='add'>
                                   <button type='submit' class="btn btn-success btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("ADD"); ?></span>
                                   </button>
                                   <?php } ?>
				</div>
                                </form>

                                <?php
                                   $sql = "select uid FROM monsters WHERE pokemon_id = 19 AND max_weight = 2410 AND id = '" . $_SESSION['id'] . "'";
                                   $result = $conn->query($sql);
                                   if ( $result->num_rows > 0 ) { $found = 1; $style = "background:#1cc88a; color:white;"; } else { $found = ""; $style = ""; }

                                   while ($row = $result->fetch_assoc()) {
                                           $uid = $row['uid'];
                                   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1" style="width:40%; min-width:150px; <?php echo $style; ?>"><?php echo i8ln("Tiny Ratatas"); ?></div>
                                   <div class="input-group-prepend">
                                       <div class="input-group-text"><?php echo i8ln("IV Min"); ?></div>
                                   </div>
				   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
				   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("Tiny Ratatas"); ?>'>
                                   <input type='hidden' id='pokemon_id' name='pokemon_id' value='19'>
                                   <input type='hidden' id='min_weight' name='max_weight' value='2410'>
                                   <input type='number' id='min_iv' name='min_iv' size=1 placeholder='0' min='0' max='100' style="width:60px; text-align:center;">
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo $uid; ?>'>
                                   <button type='submit' class="btn btn-danger btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-trash"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("DELETE"); ?></span>
                                   </button>
                                   <?php } else { ?>
                                   <input type='hidden' id='action' name='action' value='add'>
                                   <button type='submit' class="btn btn-success btn-icon-split ml-1">
                                      <span class="icon text-white-100"><i class="fas fa-plus"></i></span>
                                      <span class="text d-none d-lg-block" style="width:120px;"><?php echo i8ln("ADD"); ?></span>
                                   </button>
                                   <?php } ?>
			        </div>
                                </form>

				</div>

			    </div>

			</div>

		    </div>

                </div>
