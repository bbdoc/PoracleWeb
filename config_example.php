
<?php

// DB configuration
// Note that you can set multiple values in dbname if using multiple DBs
// $dbname should then be a comma separated list of values
// Users should register to only one DB or tool will redirect them randomly

$dbhost     = "127.0.0.1";
$dbname     = "";
$dbuser     = "";
$dbpass     = "";
$dbport     = "3306";

// Poracle Install Directoyy

$poracle_dir="";

// Discord Configuration

$redirect_url="";
$discordBotClientId = "";
$discordBotClientSecret = "";

// Image Repository 
$imgUrl="https://raw.githubusercontent.com/whitewillem/PogoAssets/resized/no_border/";

// Provide a valid URL to your Tile Server for Displaying a location map
// Arguments should correspond to your server template setup
// Use #LAT# and #LON# as placeholders for latitude and longitude
// An example is provided hereunder

$mapURL="https://youtileserver.com:9000/staticmap/pokemon?img=https://raw.githubusercontent.com/nileplumb/PkmnHomeIcons/master/pmsf_outline_shadow/pokemon_icon_150_00.png&lat=#LAT#&lon=#LON#";

// Other Configuration Items

$max_pokemon="721";
#$custom_title="";

// Debug Mode (True/False)
$debug='False';


?>
