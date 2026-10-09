
<?php

   $mons = poracle_rules('pokemon');
   $mon_cleaned = poracle_all_clean($mons) === '1' ? 1 : 0;
   $mon_distance = count($mons) ? min(array_map(function ($r) { return (int) ($r['distance'] ?? 0); }, $mons)) : 0;

?>

                <!-- Content Row -->
                <div class="row">


                    <!-- Disable Common Tracking id everythingFlagPermissions set to Deny -->
                    <?php if ($_SESSION['everythingFlagPermissions'] <> "deny" || strpos($_SESSION['type'], ":user") == false ) { ?>

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

   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'iv100')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }

                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
				   <div class="input-group-text mr-1 justify-content-center" style="width:80%; <?php echo $style; ?>"><?php echo i8ln("100% IV Pokemon"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("100% IV Pokemon"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
				   <input type='hidden' id='min_iv' name='min_iv' value='100'>
                                   <?php if ( $found == 1 ) { ?>
				   <input type='hidden' id='action' name='action' value='delete'>
				   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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

   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'iv0')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }

                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="width:80%; <?php echo $style; ?>"><?php echo i8ln("0% IV Pokemon"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("0% IV Pokemon"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
                                   <input type='hidden' id='min_iv' name='min_iv' value='0'>
                                   <input type='hidden' id='max_iv' name='max_iv' value='0'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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
   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'pvp500')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="min-width:80%; <?php echo $style; ?>"><?php echo i8ln("PvP Little Rank 1"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("PvP Little Rank 1"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
                                   <input type='hidden' id='league' name='league' value='500'>
                                   <input type='hidden' id='pvp_ranking_best' name='pvp_ranking_best' value='1'>
                                   <input type='hidden' id='pvp_ranking_worst' name='pvp_ranking_worst' value='1'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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
   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'pvp1500')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="min-width:80%; <?php echo $style; ?>"><?php echo i8ln("PvP Great Rank 1"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("PvP Great Rank 1"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
                                   <input type='hidden' id='league' name='league' value='1500'>
                                   <input type='hidden' id='pvp_ranking_best' name='pvp_ranking_best' value='1'>
                                   <input type='hidden' id='pvp_ranking_worst' name='pvp_ranking_worst' value='1'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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
   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'pvp2500')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1 justify-content-center" style="min-width:80%; <?php echo $style; ?>"><?php echo i8ln("PvP Ultra Rank 1"); ?></div>
                                   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
                                   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("PvP Ultra Rank 1"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
                                   <input type='hidden' id='league' name='league' value='2500'>
                                   <input type='hidden' id='pvp_ranking_best' name='pvp_ranking_best' value='1'>
                                   <input type='hidden' id='pvp_ranking_worst' name='pvp_ranking_worst' value='1'>
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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
                    <?php } ?>

                    <!-- QUICK PIC Card -->

                    <div class="col-xl-12 col-sm-12 col-12">

                        <div class="card shadow mb-4">

                            <div class="card-header py-3">
                                  <strong><center><?php echo i8ln("XXS & XXL Pokemon"); ?></center></strong>
                            </div>

                            <div class="card-body mb-2">

                                <div class="row d-flex justify-content-between align-items-center pl-3 pr-3">

                                <?php
   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'xxs')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1" style="width:40%; min-width:150px; <?php echo $style; ?>"><?php echo i8ln("XXS Pokemon"); ?></div>
                                   <div class="input-group-prepend">
                                       <div class="input-group-text"><?php echo i8ln("IV Min"); ?></div>
                                   </div>
				   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
				   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("XXS Pokemon"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
                                   <input type='hidden' id='size' name='size' value='1'>
                                   <input type='hidden' id='max_size' name='max_size' value='1'>
                                   <input type='number' id='min_iv' name='min_iv' size=1 placeholder='0' min='0' max='100' style="width:60px; text-align:center;">
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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
   $found = ""; $style = "";
   foreach ($mons as $r) {
      if (poracle_quick_pick_match($r, 'xxl')) { $found = 1; $style = "background:#1cc88a; color:white;"; $uid = $r['uid']; }
   }
                                ?>

                                <form action='./actions/quick_pick.php' method='POST' class="w-100">
                                <div class="input-group mt-2 justify-content-center">
                                   <div class="input-group-text mr-1" style="width:40%; min-width:150px; <?php echo $style; ?>"><?php echo i8ln("XXL Pokemon"); ?></div>
                                   <div class="input-group-prepend">
                                       <div class="input-group-text"><?php echo i8ln("IV Min"); ?></div>
                                   </div>
				   <input type='hidden' id='clean' name='clean' value='<?php echo $mon_cleaned; ?>'>
				   <input type='hidden' id='distance' name='distance' value='<?php echo $mon_distance; ?>'>
                                   <input type='hidden' id='pick' name='pick' value='<?php echo i8ln("XXL Pokemon"); ?>'>
                                   <input type='hidden' id='mon_0' name='mon_0' value='on'>
                                   <input type='hidden' id='size' name='size' value='5'>
                                   <input type='number' id='min_iv' name='min_iv' size=1 placeholder='0' min='0' max='100' style="width:60px; text-align:center;">
                                   <?php if ( $found == 1 ) { ?>
                                   <input type='hidden' id='action' name='action' value='delete'>
                                   <input type='hidden' id='uid' name='uid' value='<?php echo (int) $uid; ?>'>
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
