<?php

session_start();

function get_form_name($pokemon_id, $form_id) {

   include "./config.php";
   $monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
   $json = json_decode($monsters, true);

   foreach ($json as $name => $pokemon) {

      if ($pokemon['id'] == "$pokemon_id") { 
         if ( $pokemon['form']['id'] == "$form_id") {
            return $pokemon['form']['name'];
         }
      }
   }

}

function get_all_forms($pokemon_id) {

   include "./config.php";
   $monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
   $json = json_decode($monsters, true);
   $form_exclude = array("Shadow", "Normal", "Purified", "Copy 2019", "Fall 2019", "Spring 2020", "Vs 2019");
   $forms=array();
   $forms[0] = "Normal";

   foreach ($json as $name => $pokemon) {

      if ($pokemon['id'] == "$pokemon_id") {
         if ( $pokemon['form']['id'] <> "0" && !in_array( $pokemon['form']['name'], $form_exclude ) ) {
            $forms[$pokemon['form']['id']] = $pokemon['form']['name'];
         }
      }
   }
   return $forms;
}

function get_all_mons() {

   include "./config.php";
   if (file_exists("$poracle_dir/src/util/locale/monsters".$_SESSION['locale'].".json")) {
           $monsters = file_get_contents("$poracle_dir/src/util/locale/monsters".$_SESSION['locale'].".json"); 
   } else {
           $monsters = file_get_contents("$poracle_dir/src/util/monsters.json"); 
   }

   $json = json_decode($monsters, true);
   $monsters=array();

   foreach ($json as $name => $pokemon) {
	$arr = explode("_", $name, 2);
        $pokemon_id = $arr[0];
	$monsters[$pokemon_id] = $pokemon['name'];
   }
   $monsters=array_unique($monsters);
   return $monsters;
}

function get_mons($pokemon_id) {

   include "./config.php";
   if (file_exists("$poracle_dir/src/util/locale/monsters".$_SESSION['locale'].".json")) {
	   $monsters = file_get_contents("$poracle_dir/src/util/locale/monsters".$_SESSION['locale'].".json");
   } else {
	   $monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
   }
   $json = json_decode($monsters, true);
   $monsters=array();

   foreach ($json as $name => $pokemon) {
      $arr = explode("_", $name, 2);
      if ($arr['0'] == "$pokemon_id") {
         $found_name = $pokemon['name']; 
      }
   }
  return $found_name; 
}

function get_areas() {

   include "./config.php";
   $areas = file_get_contents("$poracle_dir/config/geofence.json");
   $json = json_decode($areas, true);
   $areas=array();

   foreach ($json as $id => $area) {
	array_push($areas, $area['name']);
   } 
   return $areas;
}

function get_raid_bosses() {

   include "./config.php";
   $bosses = file_get_contents("https://raw.githubusercontent.com/ccev/pogoinfo/info/raid-bosses.json");
   $json = json_decode($bosses, true);
   $bosses=array();

   foreach ($json as $id => $level) {
      array_push($areas, $area['name']); 
      foreach ($level as $level_id => $boss) {
         array_push($bosses, $boss);
      }
   }
   return $bosses;

}

function get_grunts() {

   include "./config.php";
   $grunts = file_get_contents("$poracle_dir/src/util/util.json");
   $json = json_decode($grunts, true);
   $grunts=array();

   foreach ($json as $key => $value) { 
      if ( $key == "gruntTypes" ) { 
	    foreach ($value as $id => $params) { 
	       if ($params['gender']=="") { $params['gender'] = "0"; }    
	       $result = $params['type'] . ',' . $params['gender'];
	       array_push($grunts, $result); 
	 }
      }
   }
   return $grunts;

}

function set_locale() {

   include "./config.php";
   $config = file_get_contents("$poracle_dir/config/local.json");
   $json = json_decode($config, true);
   foreach ($json as $key => $value) { 
      if ($key == "general") {
     	   $_SESSION['locale']=$value['locale'];
      }
   }

}


$localeData = null;
function i8ln($word)
{
    $locale = $_SESSION['locale'];
    if ($locale == "en") {
        return $word;
    }

    global $localeData;
    if ($localeData == null) {
        $filepath = 'locales/' . $locale . '.json';
        if (file_exists($filepath)) {
            $json_contents = file_get_contents($filepath);
            $localeData = json_decode($json_contents, true);
        } else {
            return $word;
        }
    }

    if (isset($localeData[$word])) {
        return $localeData[$word];
    } else {
        return $word;
    }
}




#$grunts=get_grunts();
#foreach($grunts as $key => $grunt) {
#	echo $key." | ".$grunt."<br>";
#}

