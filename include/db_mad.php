<?php

include_once "./config.php";
include_once "./include/db_connect.php";

$scan_conn = new mysqli($scan_dbhost.":".$scan_dbport, $scan_dbuser, $scan_dbpass, $scan_dbname);

function get_quest_mons() {

   global $scan_conn;
   $sql = "SELECT distinct quest_pokemon_id id, quest_pokemon_form_id form FROM trs_quest WHERE quest_pokemon_id > 0 AND quest_reward_type = 7 order by quest_pokemon_id;";
   $result = $scan_conn->query($sql);

   $mons=array();
   while($row = $result->fetch_assoc()) { 
      array_push($mons, $row['id']."_".$row['form']); 
   }

   if (isset($additional_quest_mons) && !empty($additional_quest_mons)) {
      $add_mons = explode(",", $additional_quest_mons);
      foreach ($add_mons as &$mon) {
	   array_push($mons, $mon);
      }
   }

   $mons=array_unique($mons);
   sort($mons);
   return $mons; 

}

function get_quest_items() {

   global $scan_conn;
   $sql = "SELECT distinct quest_item_id id FROM trs_quest WHERE quest_item_id > 0 order by quest_item_id;";
   $result = $scan_conn->query($sql);

   $items=array();
   while($row = $result->fetch_assoc()) {
      array_push($items, $row['id']);
   }

   return $items;

}

function get_quest_energy() {

   global $scan_conn;
   $sql = "SELECT distinct(quest_pokemon_id) id  FROM trs_quest WHERE quest_reward_type = 12 ORDER BY quest_pokemon_id;";
   $result = $scan_conn->query($sql);

   $mons=array();
   while($row = $result->fetch_assoc()) {
      array_push($mons, $row['id']);
   }

   return $mons;

}

function get_quest_candy() {

   global $scan_conn;
   $sql = "SELECT distinct(quest_pokemon_id) id  FROM trs_quest WHERE quest_reward_type = 4 ORDER BY quest_pokemon_id;";
   $result = $scan_conn->query($sql);

   $mons=array();
   while($row = $result->fetch_assoc()) {
      array_push($mons, $row['id']);
   }

   return $mons;

}


function get_raid_bosses() {

   global $scan_conn;
   $sql = "SELECT level, pokemon_id, form, evolution, costume FROM raid 
	   WHERE pokemon_id is not null and last_scanned > now() - INTERVAL 1 DAY 
           GROUP BY level, pokemon_id, form, evolution, costume ORDER BY level, pokemon_id;";
   $result = $scan_conn->query($sql);

   $bosses=array();
   while($row = $result->fetch_assoc()) {
      $pokemon_id=$row['pokemon_id'];
      $form=$row['form'];
      $costume=$row['costume'];
      $evolution=$row['evolution'];
      if ( $evolution <> '0' ) { $boss = $pokemon_id."_".$form."_".$evolution; } 
      else { $boss = $pokemon_id."_".$form."_".$costume; }
      array_push($bosses, $boss);
   }

   return $bosses;

}

function get_gym_by_id($id) {

        global $scan_conn;
        $sql = "SELECT name from gymdetails where gym_id = '".$id."'";
        $result = $scan_conn->query($sql);

        while($row = $result->fetch_assoc()) {
                $gym_name = $row['name'];
        }

        return $gym_name;

}

