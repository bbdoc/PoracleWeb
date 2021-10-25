<?php

if(!isset($_SESSION)){
    session_start();
}

$locale = @$_SESSION['locale'];

$file_monsters = "./.cache/monsters.json";
$file_items = "./.cache/items.json";
$file_raid_bosses = "./.cache/raid-bosses.json";
$file_nest_species = "./.cache/nest-species.json";
$file_grunts = "./.cache/grunts.json";

global $file_localePkmnData;
$file_localePkmnData = "./.cache/localePkmnData_".$locale.".json";
global $file_localeItemsData;
$file_localeItemsData = "./.cache/localeItemsData_".$locale.".json";

global $repo_poracle;
$repo_poracle="https://raw.githubusercontent.com/KartulUdus/PoracleJS/master";
$repo_poracle_cache="24";

global $repo_MasterData;
$repo_MasterData="https://github.com/WatWowMap/Masterfile-Generator/raw/master/master-latest-poracle.json";
$repo_MasterData_cache="24";

global $repo_locales;
$repo_locales="https://raw.githubusercontent.com/WatWowMap/pogo-translations/master/static/englishRef";
$repo_locales_cache="24";

global $repo_pogoinfo;
$repo_pogoinfo = "https://raw.githubusercontent.com/ccev/pogoinfo";
$repo_pogoinfo_cache="2";

$img_cache="24";


// Get Config Items from API and Store in Session Variables

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "X-Poracle-Secret: $api_secret\r\n"
  )
);
$context = stream_context_create($opts);

// Cache Monsters.json 

global $monsters_json;
if (file_exists($file_monsters) && (filemtime($file_monsters) > (time() - 60 * 60 * $repo_MasterData_cache ))) { 
    $monsters_json = file_get_contents($file_monsters);
} else { 
    $MasterData_json = file_get_contents($repo_MasterData);
    $json = json_decode($MasterData_json, true);
    $monsters_json = json_encode($json['monsters'],JSON_PRETTY_PRINT);
    file_put_contents($file_monsters, $monsters_json);
}

// Cache Items.json 

global $items_json;
if (file_exists($file_items) && (filemtime($file_items) > (time() - 60 * 60 * $repo_MasterData_cache ))) {
    $items_json = file_get_contents($file_items);
} else {
    $MasterData_json = file_get_contents($repo_MasterData);
    $json = json_decode($MasterData_json, true);
    $items_json = json_encode($json['items'],JSON_PRETTY_PRINT);
    file_put_contents($file_items, $items_json);
}


// Cache Util.json

global $grunts_json;
if (file_exists($file_grunts) && (filemtime($file_grunts) > (time() - 60 * 60 * $repo_poracle_cache ))) { 
    $grunts_json = file_get_contents($file_grunts);

} else { 
    $grunts_api_json = file_get_contents("$api_address/api/masterdata/grunts", false, $context);
    $json = json_decode($grunts_api_json, true);
    $grunts_json = json_encode($json,JSON_PRETTY_PRINT);
    file_put_contents($file_grunts, $grunts_json);
}

// Cache raid-bosses.json

global $bosses_json;
if (file_exists($file_raid_bosses) && (filemtime($file_raid_bosses) > (time() - 60 * 60 * $repo_pogoinfo_cache ))) {
    $bosses_json = file_get_contents($file_raid_bosses);

} else {
    #$bosses_json = file_get_contents($repo_pogoinfo."/info/raid-bosses.json");
    $bosses_json = file_get_contents($repo_pogoinfo."/v2/active/raids.json");
    file_put_contents($file_raid_bosses, $bosses_json);
}

// Cache nest_species.json

global $nest_species_json; 
if (file_exists($file_nest_species) && (filemtime($file_nest_species) > (time() - 60 * 60 * $repo_pogoinfo_cache ))) {
    $nest_species_json = file_get_contents($file_nest_species); 

} else {
    $nest_species_json = file_get_contents($repo_pogoinfo."/v2/nests/species-ids.json"); 
    file_put_contents($file_nest_species, $nest_species_json);
}

// Cache pokemonNames locale file

global $localePkmnData_json;
if (file_exists($file_localePkmnData) && (filemtime($file_localePkmnData) > (time() - 60 * 60 * $repo_locales_cache ))) { 
    $localePkmnData_json = file_get_contents($file_localePkmnData);
} else if ( @fopen($repo_locales."/pokemon_".$locale.".json", 'r') ) { 
    $localePkmnData_json = file_get_contents($repo_locales."/pokemon_".$locale.".json");
    file_put_contents($file_localePkmnData, $localePkmnData_json);
} else if (isset($locale)) {
    $localePkmnData_json = file_get_contents($repo_locales."pokemon_en.json");
}	

// Cache itemNames locale file

global $localeItemsData_json;
if (file_exists($file_localeItemsData) && (filemtime($file_localeItemsData) > (time() - 60 * 60 * $repo_locales_cache ))) {
    $localeItemsData_json = file_get_contents($file_localeItemsData);
} else if ( @fopen($repo_locales."/items_".$locale.".json", 'r') ) {
    $localeItemsData_json = file_get_contents($repo_locales."/items_".$locale.".json");
    file_put_contents($file_localeItemsData, $localeItemsData_json);
} else if (isset($locale)) {
    $localeItemsData_json = file_get_contents($repo_locales."items_en.json");
}


?>
