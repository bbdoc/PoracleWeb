<?php


function get_form_name($pokemon_id, $form_id) {

include "./config.php";
$monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
$json = json_decode($monsters, true);

foreach ($json as $name => $pokemon) {

   if ($pokemon['id'] == "$pokemon_id") { 
      if ( $pokemon['form']['id'] == "$form_id") {
         echo $pokemon['id']." | ".$pokemon['name']." | ".$pokemon['form']['id']." | ".$pokemon['form']['name']."<br>";
      }
   }
}

}

function get_all_forms($pokemon_id) {

include "./config.php";
$monsters = file_get_contents("$poracle_dir/src/util/monsters.json");
$json = json_decode($monsters, true);
$form_exclude = array("Shadow", "Normal", "Purified", "Copy 2019", "Fall 2019", "Spring 2020", "Vs 2019");

foreach ($json as $name => $pokemon) {

   if ($pokemon['id'] == "$pokemon_id") {
      if ( $pokemon['form']['id'] <> "0" && !in_array( $pokemon['form']['name'], $form_exclude ) ) {
         echo $pokemon['id']." | ".$pokemon['name']." | ".$pokemon['form']['id']." | ".$pokemon['form']['name']."<br>";
      }
   }
}

}


echo "TEST GET_FORM_NAME : <br>";
get_form_name("19","46");
echo "<hr>";


echo "TEST GET_ALL_FORMS : <br>";
get_all_forms("201");
echo "<hr>";

?>
