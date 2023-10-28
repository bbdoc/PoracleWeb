<?php

@include_once "./config.php";
@include_once "./include/db_connect.php";
@include_once "./include/cache_handler.php";

if(!isset($_SESSION)){
    session_start();
}

global $localeData;
global $localePkmnData;
global $localeItemsData;

function get_form_name($pokemon_id, $form_id) {

   global $monsters_json;
   $json = json_decode($monsters_json, true);

   foreach ($json as $name => $pokemon) {

      if ($pokemon['id'] == "$pokemon_id") { 
         if ( $pokemon['form']['id'] == "$form_id" && $pokemon['form']['id'] <> 0) {
            return $pokemon['form']['name'];
         }
      }
   }
}

function get_item_name($item_id) {

   global $items_json;
   $json = json_decode($items_json, true);

   foreach ($json as $id => $item) { 
      if ($id == "$item_id") { 
            return translate_item($item['name']);
      }
   }
}

function get_all_forms($pokemon_id) {

   global $monsters_json;
   $json = json_decode($monsters_json, true);
   $form_exclude = array("Shadow", "Purified", "Copy 2019", "Fall 2019", "Spring 2020", "Vs 2019");
   $forms=array();
   $forms[0] = "All";

   foreach ($json as $name => $pokemon) {

      if ($pokemon['id'] == "$pokemon_id") {
         if ( $pokemon['form']['id'] <> "0" && !in_array( ucfirst($pokemon['form']['name']), $form_exclude ) ) {
            $forms[$pokemon['form']['id']] = $pokemon['form']['name'];
         }
      }
   }
   return $forms;
}

function stripAccents($str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function get_all_mons() {

   global $monsters_json;
   $json = json_decode($monsters_json, true);
   $monsters=array();

   foreach ($json as $name => $pokemon) {

	$arr = explode("_", $name, 2);
        $pokemon_id = $arr[0];
	$monsters[$pokemon_id] = translate_mon($pokemon['name']);

	// Append type to Mon Name
	foreach ($pokemon['types'] as $id => $type) {
		$monsters[$pokemon_id] .= "_".i8ln($type['name']);
	}

   }
   $monsters=array_unique($monsters);
   return $monsters;
}

function get_nest_species() {

   global $nest_species_json; 
   $json = json_decode($nest_species_json, true);
   $nest_species=$json['all']; 
   return $nest_species;
}

function get_mons($pokemon_id) {

   global $monsters_json;
   $found_name="";
   $json = json_decode($monsters_json, true);
   $monsters=array();

   foreach ($json as $name => $pokemon) {
      $arr = explode("_", $name, 2);
      if ($arr['0'] == "$pokemon_id") {
         $found_name = translate_mon($pokemon['name']); 
      }
   }
  return $found_name; 
}

function get_matching_ids($search) {

    $search = strtolower($search);
    global $monsters_json;
    $json = json_decode($monsters_json, true);

    $ids = array(0);
    foreach ($json as $name => $pokemon) {
	    $arr = explode("_", $name, 2);
	    // Match on Pokemon Name
	    if ( strpos(strtolower(translate_mon($pokemon['name'])),$search) !== false )
	    {
		    array_push($ids,$arr['0']);
	    } 
	    // Match on Pokemon Type
	    foreach ($pokemon['types'] as $id => $type) {
		    if ( strpos(strtolower(i8ln($type['name'])),$search) !== false )
		    {
			    array_push($ids,$arr['0']);
		    }
	    }
            // Match on ID
            if ( $search == $arr['0'] )
            {
                    array_push($ids,$arr['0']);
            }
    }
    $ids = array_unique($ids);
    return $ids;
}


function translate_mon($word)
{
    $locale = @$_SESSION['locale']; 
    if ($locale == "en") {
        return $word; exit();
    }

    global $localePkmnData;
    global $localePkmnData_json;

    if ($localePkmnData == null) {
        $localePkmnData = json_decode($localePkmnData_json, true);
    }

    if (isset($localePkmnData[$word])) {
        return $localePkmnData[$word];
    } else {
        return $word;
    }
}

function translate_item($word)
{
    $locale = @$_SESSION['locale'];
    if ($locale == "en") {
        return $word; exit();
    }

    global $localeItemsData;
    global $localeItemsData_json;

    if ($localeItemsData == null) {
        $localeItemsData = json_decode($localeItemsData_json, true);
    }

    if (isset($localeItemsData[$word])) {
        return $localeItemsData[$word];
    } else {
        return $word;
    }
}

function get_areas() {

    $areas = $_SESSION['areas']; 
    $areas = array();

    foreach ($_SESSION['areas'] as $i => $area) {

        foreach ($area as $type => $value) { 
                if ($type === "group" ) { $group = $value; }
                if ($type === "name" ) { $areaName = $value;}
                if ($type === "userSelectable" ) { $userselectable = $value;}
                if ($type === "description" ) { $description = $value;}
	}

	if ( $userselectable == 1 || isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) ) {
            if(array_key_exists($group, $areas)){
                $groupAreas = $areas[$group];
                array_push($groupAreas, $areaName);
                $areas[$group] = $groupAreas;
            } else {
                $areas[$group] = array($areaName);
   	    }
	}

    }

    ksort($areas);

    return $areas;
}

function get_raid_bosses_json() {

   include_once "./config.php";
   include_once "./include/db_connect.php";
   global $bosses_json;
   $json = json_decode($bosses_json, true);
   $bosses=array(); 

   foreach ($json as $level => $list_id) { 

      foreach ($list_id as $id => $boss_values) { 
         
         $id = $boss_values['id'];
         $form = $boss_values['form'];
         $evolution = $boss_values['temp_evolution_id'];

         if ( isset($evolution) ) { $boss=$id."_".$form."_".$evolution; } else { $boss=$id."_".$form; }
         array_push($bosses, $boss);

      }

   }
   return $bosses;

}

function get_grunt($type,$gender) {

   global $grunts_json;
   $json = json_decode($grunts_json, true);
   $grunts=array();

   foreach ($json as $key => $value) { 
	   if ( strtoupper($value['type']) == strtoupper($type) && $value['gender'] == $gender ) 
	   { 
                   return $key;
	   }
	   else if ( strtoupper($value['type']) == strtoupper($type) && $gender == 0 )
           {
                   return $key;
           }

   }

}

function get_egg_raid_name($id) {
	if ( $id == "0") {
	} else if ( $id == "1") {
		$name = "Level 1";
	} else if ( $id == "2") {
		$name = "Level 2";
	} else if ( $id == "3") {
		$name = "Level 3";
	} else if ( $id == "4") {
		$name = "Level 4";
	} else if ( $id == "5") {
		$name = "Legendary";
	} else if ( $id == "6") {
		$name = "Mega";
	} else if ( $id == "7") {
		$name = "Mega Legendary";
	} else if ( $id == "8") {
		$name = "Ultra Beast";
	} else if ( $id == "9") {
		$name = "Elite";
	} else if ( $id == "10") {
		$name = "Primal";
	} else if ( $id == "11") {
		$name = "Level 1 Shadow";
	} else if ( $id == "12") {
		$name = "Level 2 Shadow";
	} else if ( $id == "13") {
		$name = "Level 3 Shadow";
	} else if ( $id == "14") {
		$name = "Level 4 Shadow";
	} else if ( $id == "15") {
		$name = "Shadow Legendary";
	}

	return $name;

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
        } else if ( $id == "505") {
                $lure_name = "Rainy Lure";
        } else if ( $id == "506") {
                $lure_name = "Sparkly Lure";
        }

        return $lure_name;	

}

function get_size_name($id) {

        if ($id == '1') { $size_name = "XXS"; }
	else if ($id == '2') { $size_name = "XS";  }
	else if ($id == '3') { $size_name = "M";  }
	else if ($id == '4') { $size_name = "XL"; }
	else if ($id == '5') { $size_name = "XXL"; }

        return $size_name;	

}

function get_gym_name($id) {

        if ( $id == "0") {
                $name = "Harmony";
        } else if ( $id == "1") {
                $name = "Mystic";
        } else if ( $id == "2") {
                $name = "Valor";
        } else if ( $id == "3") {
                $name = "Instinct";
	} else if ( $id == "4") {
		$name = "All";
	}

        return $name;

}

function get_gym_color($id) {

        if ( $id == "0") {
                $color = "Grey";
        } else if ( $id == "1") {
                $color = "Blue";
        } else if ( $id == "2") {
                $color = "Red";
        } else if ( $id == "3") {
                $color = "Yellow";
        }

        return $color;

}

function set_locale() {

   global $conn;
   if (isset($_SESSION['server_locale'])) {
      $_SESSION['locale'] = $_SESSION['server_locale'];
   }

   if (isset($_SESSION['id'])) {
      include_once "./config.php";
      include_once "./include/db_connect.php";
      $sql = "select language FROM humans WHERE id = '" . $_SESSION['id'] . "'"; 
      $result = $conn->query($sql) or die(mysqli_error($conn));
      while ($row = $result->fetch_assoc()) {  
         if ( $row['language'] <> "" ) { 
            $_SESSION['locale'] = $row['language'];
         }
      }
   }

}

function get_address($lat, $lon) {

   $filepath=$_SESSION['providerURL']."/reverse?lat=$lat&lon=$lon&format=json";
   if ( strlen($_SESSION['staticKey']) == 32  ) {
           $filepath.="&key=".$_SESSION['staticKey'];
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

function stripComments( $str ) 
{
        $str = preg_replace('![ \t]*[^:]//.*[ \t]*[\r\n]!', '', $str);       //Strip single-line comments: '// comment'
        return $str;
}


function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    if($result !== FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function reloadPokemon()
{

   // Include one Dir Back as Calling from /actions	
   include "../config.php";
   include "../include/db_connect.php";

   $opts = array(
     'http'=>array(
       'method'=>"GET",
       'header'=>"Accept-language: en\r\n" .
                 "X-Poracle-Secret: $api_secret\r\n"
     )
   );
   $context = stream_context_create($opts);

   $reload = file_get_contents("$api_address/api/tracking/pokemon/refresh", false, $context);

}

function getMiniMap($latitude, $longitude, $distance)
{

   include "./config.php";
   include "./include/db_connect.php";

   $opts = array(
     'http'=>array(
       'method'=>"GET",
       'header'=>"Accept-language: en\r\n" .
                 "X-Poracle-Secret: $api_secret\r\n"
     )
   );
   $context = stream_context_create($opts);

   $urlkey=$latitude."_".$longitude."_".$distance;
   $urlkey=str_replace('.', '_', $urlkey);
   $fileURL = ".cache/MiniMaps/".$urlkey.".png";

   if (!file_exists($fileURL)) { 
           $config = file_get_contents("$api_address/api/geofence/distanceMap/$latitude/$longitude/$distance", false, $context); 
           $json = json_decode($config, true); 
           if ( $json['status']=="ok" ) { 
		   $mapURL = $json['url'];  
		   $map_image = file_get_contents($mapURL);
                   file_put_contents($fileURL, $map_image);
           }
   }

   return $fileURL;
}

function getLocationMap($latitude, $longitude)
{

   include "./config.php";
   include "./include/db_connect.php";

   $opts = array(
     'http'=>array(
       'method'=>"GET",
       'header'=>"Accept-language: en\r\n" .
                 "X-Poracle-Secret: $api_secret\r\n"
     )
   );
   $context = stream_context_create($opts);

   $urlkey=$latitude."_".$longitude;
   $urlkey=str_replace('.', '_', $urlkey);
   $fileURL = ".cache/LocationMaps/".$urlkey.".png";

   if (!file_exists($fileURL)) {
           $config = file_get_contents("$api_address/api/geofence/locationMap/$latitude/$longitude", false, $context);
           $json = json_decode($config, true);
           if ( $json['status']=="ok" ) {
                   $mapURL = $json['url'];
                   $map_image = file_get_contents($mapURL);
                   file_put_contents($fileURL, $map_image);
           }
   }

   return $fileURL;
}

function default_distance($table) {

   global $conn;
   if (isset($_SESSION['id'])) {
      include_once "./config.php";
      include_once "./include/db_connect.php";
      $sql = "select max(distance) distance FROM $table WHERE id = '" . $_SESSION['id'] . "'";
      $sql = "SELECT distance, count(*) FROM $table WHERE id = '" . $_SESSION['id'] . "' GROUP BY distance ORDER BY count(*) DESC LIMIT 1"; 
      $result = $conn->query($sql) or die(mysqli_error($conn));
      while ($row = $result->fetch_assoc()) { $default_distance = $row['distance']; }
   }

   if ( !isset($default_distance) ) { $default_distance = 0; }
   if ( $default_distance == 0 && @$disable_areas == "True" ) { $default_distance = $_SESSION['defaultDistance']; };

   return $default_distance;

}

