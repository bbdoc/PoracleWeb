<?php

// Build v2 tracking rules from PoracleWeb's form posts. null = "any"/off:
// poracle_track_add leaves nulls out, poracle_track_update uses them to clear.

const PORACLE_INCIDENTS = array(7 => 'gold-stop', 8 => 'kecleon', 9 => 'showcase');

// The value of one named radio field with its prefix stripped; null when the
// field is absent or does not carry the prefix.
function form_radio(array $post, $field, $prefix)
{
   if (isset($post[$field]) && is_string($post[$field]) && strpos($post[$field], $prefix) === 0) {
      return substr($post[$field], strlen($prefix));
   }
   return null;
}

function form_int(array $post, $key)
{
   if (!isset($post[$key]) || $post[$key] === '' || !is_numeric($post[$key])) {
      return null;
   }
   return (int) $post[$key];
}

function form_common(array $post)
{
   $clean = form_radio($post, 'clean', 'clean_');
   if ($clean === null && isset($post['clean'])) {
      $clean = (string) $post['clean'];    // quick pick posts a bare 0/1
   }
   $distance = form_int($post, 'distance');
   $ping = isset($post['content']) ? trim($post['content']) : '';
   return array(
      'distance' => $distance ? $distance : null,
      'clean' => $clean === '1' ? true : null,
      'template' => (isset($post['template']) && $post['template'] !== '') ? $post['template'] : null,
      'ping' => $ping !== '' ? $ping : null,
   );
}

function poracle_team_enum($team)
{
   $teams = array(0 => 'harmony', 1 => 'mystic', 2 => 'valor', 3 => 'instinct');
   return isset($teams[(int) $team]) ? $teams[(int) $team] : 'any';
}

function poracle_team_id($enum)
{
   $ids = array('harmony' => 0, 'mystic' => 1, 'valor' => 2, 'instinct' => 3);
   return isset($ids[$enum]) ? $ids[$enum] : 4;
}

function poracle_gender_enum($gender)
{
   $genders = array(1 => 'male', 2 => 'female', 3 => 'genderless');
   return isset($genders[(int) $gender]) ? $genders[(int) $gender] : null;
}

function poracle_gender_id($enum)
{
   $ids = array('male' => 1, 'female' => 2, 'genderless' => 3);
   return isset($ids[$enum]) ? $ids[$enum] : 0;
}

function form_pokemon_filters(array $post, array $pvpMinCp, $defaultCap)
{
   $f = array(
      'min_iv' => !empty($post['noiv']) ? null : (form_int($post, 'min_iv') !== null ? form_int($post, 'min_iv') : 0),
      'max_iv' => form_int($post, 'max_iv'),
      'min_cp' => form_int($post, 'min_cp'),
      'max_cp' => form_int($post, 'max_cp'),
      'min_level' => form_int($post, 'min_level'),
      'max_level' => form_int($post, 'max_level'),
      'min_weight' => form_int($post, 'min_weight'),
      'max_weight' => form_int($post, 'max_weight'),
      'atk' => form_int($post, 'atk'),
      'def' => form_int($post, 'def'),
      'sta' => form_int($post, 'sta'),
      'max_atk' => form_int($post, 'max_atk'),
      'max_def' => form_int($post, 'max_def'),
      'max_sta' => form_int($post, 'max_sta'),
      'pvp_ranking_league' => null,
      'pvp_ranking_best' => null,
      'pvp_ranking_worst' => null,
      'pvp_ranking_min_cp' => null,
      'pvp_ranking_cap' => null,
   );

   $league = form_int($post, 'league');
   if ($league) {
      $minCp = form_int($post, 'pvp_ranking_min_cp');
      if ($minCp === null) {
         $minCp = isset($pvpMinCp[$league]) ? (int) $pvpMinCp[$league] : 0;
      }
      $cap = form_radio($post, 'cap', 'cap_');
      $cap = ($cap !== null) ? (int) $cap : (int) $defaultCap;
      $f['pvp_ranking_league'] = $league;
      $f['pvp_ranking_best'] = form_int($post, 'pvp_ranking_best');
      $f['pvp_ranking_worst'] = form_int($post, 'pvp_ranking_worst');
      $f['pvp_ranking_min_cp'] = $minCp ? $minCp : null;
      $f['pvp_ranking_cap'] = $cap ? $cap : null;
   }

   $gender = form_radio($post, 'gender', 'gender_');
   $f['gender'] = $gender === null ? null : poracle_gender_enum($gender);

   $form = form_radio($post, 'form', 'form_');
   $f['form'] = ($form !== null && (int) $form > 0) ? (int) $form : null;

   $size = form_radio($post, 'size', 'size_');
   if ($size !== null) {
      $f['size'] = ((int) $size > 0) ? (int) $size : null;
      $f['max_size'] = $f['size'];
   } else {
      // quick pick posts bare size / max_size
      $f['size'] = form_int($post, 'size') > 0 ? form_int($post, 'size') : null;
      $f['max_size'] = form_int($post, 'max_size') > 0 ? form_int($post, 'max_size') : null;
   }
   return $f;
}

function form_pokemon_rules(array $post, array $pvpMinCp, $defaultCap)
{
   $base = array_merge(form_common($post), form_pokemon_filters($post, $pvpMinCp, $defaultCap));
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'mon_') !== 0) {
         continue;
      }
      $id = (int) substr($key, 4);
      $rules[] = array_merge($base, array('pokemon_id' => $id ? $id : null));
      if ($id === 0) {
         break;    // "all pokemon" replaces any individual picks
      }
   }
   return $rules;
}

function form_raid_rules(array $post)
{
   $common = form_common($post);
   $gym = (isset($post['gym_id']) && $post['gym_id'] !== '' && $post['gym_id'] !== 'ALL') ? $post['gym_id'] : null;
   $rsvpNames = array('1' => 'rsvp', '2' => 'rsvp_only');
   $rsvp = (isset($post['rsvp']) && isset($rsvpNames[$post['rsvp']])) ? $rsvpNames[$post['rsvp']] : null;
   $shared = array_merge($common, array('gym_id' => $gym, 'rsvp_changes' => $rsvp));

   $out = array('egg' => array(), 'raid' => array());
   foreach ($post as $key => $value) {
      if (strpos($key, 'egg_') === 0) {
         $out['egg'][] = array_merge($shared, array('level' => (int) substr($key, 4)));
      } elseif (strpos($key, 'raid_') === 0) {
         $out['raid'][] = array_merge($shared, array('level' => (int) substr($key, 5), 'pokemon_id' => null));
      } elseif (strpos($key, 'mon_') === 0) {
         $parts = explode('_', $key);    // mon_<id>_<form>[_<mega>]
         $form = isset($parts[2]) ? (int) $parts[2] : 0;
         $mega = isset($parts[3]) ? (int) $parts[3] : 0;
         $out['raid'][] = array_merge($shared, array(
            'pokemon_id' => (int) $parts[1],
            'form' => $form ? $form : null,
            'evolution' => $mega ? $mega : null,
         ));
      }
   }
   return $out;
}

function form_quest_rules(array $post)
{
   $common = form_common($post);
   $types = array('item_' => 2, 'energy_' => 12, 'candy_' => 4);
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'mon_') === 0) {
         $parts = explode('_', $key);    // mon_<id>_<form>
         $form = isset($parts[2]) ? (int) $parts[2] : 0;
         $rules[] = array_merge($common, array('reward_type' => 7, 'reward' => (int) $parts[1], 'form' => $form ? $form : null));
         continue;
      }
      foreach ($types as $prefix => $rewardType) {
         if (strpos($key, $prefix) === 0) {
            $reward = (int) substr($key, strlen($prefix));
            $rules[] = array_merge($common, array('reward_type' => $rewardType, 'reward' => $reward ? $reward : null));
         }
      }
   }
   return $rules;
}

function form_invasion_rules(array $post)
{
   $common = form_common($post);
   $incidentIds = array_flip(PORACLE_INCIDENTS);
   $out = array('invasion' => array(), 'incident' => array());
   foreach ($post as $key => $value) {
      if (strpos($key, 'grunt_') !== 0) {
         continue;
      }
      $gender = (int) substr($key, 6, 1);    // grunt_<gender>_<name>
      $name = strtolower(str_replace('_', ' ', substr($key, 8)));
      if (isset($incidentIds[$name])) {
         $out['incident'][] = array_merge($common, array('display_type' => $incidentIds[$name]));
      } else {
         $out['invasion'][] = array_merge($common, array('grunt_type' => $name, 'gender' => poracle_gender_enum($gender)));
      }
   }
   return $out;
}

function form_lure_rules(array $post)
{
   $common = form_common($post);
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'lure_') === 0) {
         $rules[] = array_merge($common, array('lure_id' => (int) substr($key, 5)));
      }
   }
   return $rules;
}

function form_nest_rules(array $post)
{
   $common = form_common($post);
   $minSpawns = form_int($post, 'min_spawns');
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'nest_') === 0) {
         $id = (int) substr($key, 5);
         $rules[] = array_merge($common, array('pokemon_id' => $id ? $id : null, 'min_spawn_avg' => $minSpawns ? $minSpawns : null));
      }
   }
   return $rules;
}

function form_gym_rules(array $post)
{
   $common = form_common($post);
   $flags = array(
      'slot_changes' => form_radio($post, 'slot', 'slot_') === '1' ? true : null,
      'battle_changes' => form_radio($post, 'battle', 'battle_') === '1' ? true : null,
   );
   $rules = array();
   foreach ($post as $key => $value) {
      if (strpos($key, 'gym_') === 0 && $key !== 'gym_id') {
         $rules[] = array_merge($common, $flags, array('team' => poracle_team_enum(substr($key, 4))));
      }
   }
   return $rules;
}
