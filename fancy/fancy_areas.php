<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POracle Configurator</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">
  <link rel="stylesheet" type="text/css" href="css/add_style.css?v=<?=time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
</head>
<body style="background-color:#FFFFFF; color: grey;">


<?php

    // Check Current Selection

    $sql = "select area FROM humans WHERE id = '".$_SESSION['id']."'";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) { $existing_area = $row['area']; }

    // Add Hidden Fancy Box Area Selection

    echo "
    <div style='display: none;' id='areas'>
    <form action='./form_action.php' method='POST'>
    ";

    echo "<center>";
    echo "<ul>\n";

    $areas = shell_exec('grep name '.$poracle_dir.'/config/geofence.json | cut -d\" -f4 | tr "\n" "," | sed s/,$//g');
    $areas = explode(',', $areas);
    sort($areas);
    foreach ($areas as &$area) {

       if ( stristr($existing_area, $area) > '') { $checked = 'checked'; } else { $checked = ''; };

       echo "<li><input type='checkbox' name='area_$area' id='area_$area' $checked/>\n";
       echo "<label for='area_$area' style='width:200px;'>$area</label>\n";
       echo "</li>\n";
    } 

    echo "</ul>\n";

    echo "

	<center><br>
        <input type='hidden' id='type' name='action' value='areas'> 
        <input type='submit' name='update' value='Update' class='button_update'>
        </center>


    </form>
    </div>
    ";

?>
