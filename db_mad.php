<?php

function get_quest_mons() {

   include "./config.php";

   $conn = new mysqli($scan_dbhost.":".$scan_dbport, $scan_dbuser, $scan_dbpass, $scan_dbname);
   $sql = "SELECT distinct quest_pokemon_id id FROM trs_quest WHERE quest_pokemon_id > 0 AND quest_reward_type = 7 order by quest_pokemon_id;";
   $result = $conn->query($sql);

   $mons=array();
   while($row = $result->fetch_assoc()) { 
      array_push($mons, $row['id']); 
   }

   return $mons; 

}

function get_quest_items() {

   include "./config.php";

   $conn = new mysqli($scan_dbhost.":".$scan_dbport, $scan_dbuser, $scan_dbpass, $scan_dbname);
   $sql = "SELECT distinct quest_item_id id FROM trs_quest WHERE quest_item_id > 0 order by quest_item_id;";
   $result = $conn->query($sql);

   $items=array();
   while($row = $result->fetch_assoc()) {
      array_push($items, $row['id']);
   }

   return $items;

}

function get_quest_energy() {

   include "./config.php";

   $conn = new mysqli($scan_dbhost.":".$scan_dbport, $scan_dbuser, $scan_dbpass, $scan_dbname);
   $sql = "SELECT distinct quest_pokemon_id id AS quest_energy_pokemon_id FROM trs_quest WHERE quest_reward_type = 12;";
   $result = $conn->query($sql);

   $mons=array();
   while($row = $result->fetch_assoc()) {
      array_push($mons, $row['id']);
   }

   return $mons;

}


