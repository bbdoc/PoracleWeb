<?php

if(!isset($_SESSION)){
    session_start();
}

global $localeData;
global $localePkmnData;

function get_form_name($pokemon_id, $form_id) {

   include "./config.php";
   $monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
   $json = json_decode($monsters, true);

   foreach ($json as $name => $pokemon) {

      if ($pokemon['id'] == "$pokemon_id") { 
         if ( $pokemon['form']['id'] == "$form_id" && $pokemon['form']['id'] <> 0) {
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
         if ( $pokemon['form']['id'] <> "0" && !in_array( ucfirst($pokemon['form']['name']), $form_exclude ) ) {
            $forms[$pokemon['form']['id']] = $pokemon['form']['name'];
         }
      }
   }
   return $forms;
}

function get_all_mons() {

   include "./config.php";
   $monsters = file_get_contents("$poracle_dir/src/util/monsters.json"); 
   $json = json_decode($monsters, true);
   $monsters=array();

   foreach ($json as $name => $pokemon) {
	$arr = explode("_", $name, 2);
        $pokemon_id = $arr[0];
	$monsters[$pokemon_id] = translate_mon($pokemon['name']);
   }
   $monsters=array_unique($monsters);
   return $monsters;
}

function get_mons($pokemon_id) {

   include "./config.php";
   $found_name="";
   $monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
   $json = json_decode($monsters, true);
   $monsters=array();

   foreach ($json as $name => $pokemon) {
      $arr = explode("_", $name, 2);
      if ($arr['0'] == "$pokemon_id") {
         $found_name = translate_mon($pokemon['name']); 
      }
   }
  return $found_name; 
}

function translate_mon($word)
{
    include "./config.php";
    $locale = @$_SESSION['locale'];
    if ($locale == "en") {
        return $word;
    }

    global $localePkmnData;
    if ($localePkmnData == null) {
        $filepath = "$poracle_dir/src/util/locale/pokemonNames_".$locale.".json";
        if (file_exists($filepath)) {
            $json_contents = file_get_contents($filepath);
            $localePkmnData = json_decode($json_contents, true);
        } else {
            return $word;
	}
    }

    if (isset($localePkmnData[$word])) {
        return $localePkmnData[$word];
    } else {
        return $word;
    }
}

function get_areas() {

    include "./config.php";
    $areas = file_get_contents("$poracle_dir/config/geofence.json");
    $json = json_decode($areas, true);
    $areas = array();

    if (@$json['type'] == "FeatureCollection" || isset($json['features'])) {
        $listOfFeatures = $json['features'];
        foreach ($listOfFeatures as $i => $feature) {
            $areaName = $feature['properties']['name'];

            if(isset($feature['properties']['group'])) {
                $group = $feature['properties']['group'];
            }else {
                $group = "";
            }

            if(array_key_exists($group, $areas)){
                $groupAreas = $areas[$group];
                array_push($groupAreas, $areaName);
                $areas[$group] = $groupAreas;
            }else{
                $areas[$group] = array($areaName);
            }
        }
    } else {
        foreach ($json as $i => $area) {
            if(array_key_exists("", $areas)){
                $groupAreas = $areas[""];
                array_push($groupAreas,  $area['name']);
                $areas[""] = $groupAreas;
            }else{
                $areas[""] = array( $area['name']);
            }
        }
    }
    ksort($areas);

    return $areas;
}

function get_raid_bosses_json() {

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

function get_lure_name($id) {

	if ( $id == "0") {
                $lure_name = "ALL";
        } else if ( $id == "501") { 
		$lure_name = "Normal Lure";
	} else if ( $id == "502") {
		$lure_name = "Glacial Lure";
	} else if ( $id == "503") {
                $lure_name = "Mossy Lure";
        } else if ( $id == "504") {
                $lure_name = "Magnetic Lure";
        }

        return $lure_name;	

}

function set_locale() {

   include "./config.php";
   include "./db_connect.php";
   $sql = "select language FROM humans WHERE id = '" . $_SESSION['id'] . "'"; 
   $result = $conn->query($sql) or die(mysqli_error($conn));
   while ($row = $result->fetch_assoc()) {  
      if ( $row['language'] <> "" ) { 
         $_SESSION['locale'] = $row['language'];
      } else { 
         $config = file_get_contents("$poracle_dir/config/local.json");
         $json = json_decode($config, true);
         foreach ($json as $key => $value) {
            if ($key == "general") {
              $_SESSION['locale']=$value['locale'];
            }
         }
      }
   }
}

function get_address($lat, $lon) {

   include "./config.php";

   $config = file_get_contents("$poracle_dir/config/local.json");
   $json = json_decode($config, true);
   foreach ($json as $key => $value) {
      if ($key == "geocoding") {
        $nominatim=$value['providerURL']; 
	$statickey=$value['staticKey'][0]; 
      }
   }

   $filepath="$nominatim/reverse?lat=$lat&lon=$lon&format=json";
   if ( strlen($statickey) == 32  ) {
           $filepath.="&key=".$statickey;
   }

   $request = file_get_contents($filepath);

   $json = json_decode($request, true);

   foreach ($json as $key => $value) {
      if ( is_array($value) ) {
         foreach ($value as $key => $value2) {
            if ( "$key" == "road") { $road = $value2;} 
            if ( "$key" == "village") { $village=$value2;} 
            if ( "$key" == "town") { $town=$value2;} 
            if ( "$key" == "city") { $city=$value2;} 
            if ( "$key" == "city_district") { $city_district=$value2;} 
            if ( "$key" == "country_code") { $country=strtoupper($value2);} 
         }
      }
      if ( @$city_district <> "" ) {  $city=$city_district; }
      if ( @$town <> "" ) {  $city=$town; }
      if ( @$village <> "" ) {  $city=$village; }
   }
   $address = @$road." | ".@$city." | ".@$country;
   return $address;
}


function i8ln($word)
{
    $locale = @$_SESSION['locale'];
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

function set_defaults()
{
   include "./config.php"; 
   global $MaxRank, $GreatMinCP, $UltraMinCP;
   $config = file_get_contents("$poracle_dir/config/local.json");
   $json = json_decode($config, true);
   $MaxRank = 4096;
   $GreatMinCP = 0;
   $UltraMinCP = 0;
   #foreach ($json as $key => $value) {
   #   if ($key == "pvp") {
   #     if (isset($value['pvpFilterMaxRank'])) { $MaxRank=$value['pvpFilterMaxRank']; } 
   #     if (isset($value['pvpFilterGreatMinCP'])) { $GreatMinCP=$value['pvpFilterGreatMinCP']; } 
   #     if (isset($value['pvpFilterUltraMinCP'])) { $UltraMinCP=$value['pvpFilterUltraMinCP']; }
   #   }
   #}
}


# Execute Set Defaults so defaults are available on all pages
set_defaults();

#$grunts=get_grunts();
#foreach($grunts as $key => $grunt) {
#	echo $key." | ".$grunt."<br>";
#}
