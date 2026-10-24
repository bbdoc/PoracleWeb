<?php
use PHPUnit\Framework\TestCase;

class PoracleTrackingTest extends TestCase
{
   private $calls = array();

   private function api(array $responses)
   {
      $calls = &$this->calls;
      return new PoracleApi('http://p', 's', function ($method, $url, $headers, $body) use (&$calls, &$responses) {
         $calls[] = array('method' => $method, 'url' => $url, 'body' => $body === null ? null : json_decode($body, true));
         return array_shift($responses);
      });
   }

   private $snapshot = array('tracking' => array(
      'lure' => array(
         array('uid' => 1, 'profile_no' => 1, 'lure_id' => 501, 'distance' => null, 'clean' => true),
         array('uid' => 2, 'profile_no' => 2, 'lure_id' => 502, 'distance' => 500, 'clean' => null),
         array('uid' => 3, 'profile_no' => 1, 'lure_id' => 0, 'distance' => 500, 'clean' => true),
      ),
   ));

   public function testRulesInFiltersByProfile()
   {
      $this->assertSame(array(1, 3), array_column(poracle_rules_in($this->snapshot, 'lure', 1), 'uid'));
      $this->assertCount(3, poracle_rules_in($this->snapshot, 'lure', null));
      $this->assertSame(array(), poracle_rules_in($this->snapshot, 'nest', 1));
   }

   public function testAllClean()
   {
      $this->assertSame('1', poracle_all_clean(poracle_rules_in($this->snapshot, 'lure', 1)));
      $this->assertSame('0', poracle_all_clean(poracle_rules_in($this->snapshot, 'lure', null)));
      $this->assertNull(poracle_all_clean(array()));
   }

   public function testDistances()
   {
      $all = poracle_rules_in($this->snapshot, 'lure', null);
      $this->assertSame(500, poracle_common_distance($all));
      $this->assertSame(0, poracle_shared_distance($all));
      $this->assertSame(500, poracle_shared_distance(array(array('distance' => 500), array('distance' => 500))));
      $this->assertSame(0, poracle_common_distance(array()));
   }

   public function testCopyPlanGroupsByTypeAndProfileWithoutUids()
   {
      $plan = poracle_copy_plan($this->snapshot, null);
      $this->assertSame(array(array('lure', 1, 2), array('lure', 2, 1)), array_map(function ($p) { return array($p['type'], $p['profile_no'], count($p['rules'])); }, $plan));
      $this->assertArrayNotHasKey('uid', $plan[0]['rules'][0]);
      $this->assertSame(array(array('lure', 2, 1)), array_map(function ($p) { return array($p['type'], $p['profile_no'], count($p['rules'])); }, poracle_copy_plan($this->snapshot, 2)));
   }

   public function testRuleBodyStripsResponseKeysAndNulls()
   {
      $this->assertSame(array('lure_id' => 501, 'clean' => true),
         poracle_rule_body(array('uid' => 9, 'profile_no' => 1, 'description' => 'x', 'lure_id' => 501, 'distance' => null, 'clean' => true)));
   }

   public function testAddSendsExplicitProfile()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      poracle_track_add($api, '42', 'lure', array(array('lure_id' => 501, 'distance' => null)), 2);
      $this->assertSame('POST', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure?profile=2', $this->calls[0]['url']);
      $this->assertSame(array(array('lure_id' => 501)), $this->calls[0]['body']);
   }

   public function testUpdateKeepsFieldsTheFormDoesNotSend()
   {
      $api = $this->api(array(
         array(200, '{"rules":[{"uid":7,"lure_id":501,"distance":100,"clean":true,"edit":true,"override_areas":["north"],"ping":"@here","template":null}]}'),
         array(200, '{"created":[],"updated":[{"uid":8}],"unchanged":[]}'),
      ));
      poracle_track_update($api, '42', 'lure', 7, array('distance' => 250, 'clean' => null, 'ping' => '@here'), 1);
      $this->assertSame('GET', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure/7?profile=1', $this->calls[0]['url']);
      $this->assertSame('PUT', $this->calls[1]['method']);
      $this->assertSame(array('lure_id' => 501, 'distance' => 250, 'edit' => true, 'override_areas' => array('north'), 'ping' => '@here'), $this->calls[1]['body']);
   }

   public function testUpdateThrowsWhenRuleMissing()
   {
      foreach (array('{"rules":[]}', '{}') as $body) {
         $api = $this->api(array(array(200, $body)));
         try {
            poracle_track_update($api, '42', 'lure', 7, array('distance' => 250), 1);
            $this->fail('expected PoracleApiException');
         } catch (PoracleApiException $e) {
            $this->assertSame(404, $e->status);
            $this->assertSame('Rule not found', $e->detail);
         }
         $this->assertCount(1, $this->calls);
         $this->calls = array();
      }
   }

   public function testDeleteUidsSkipsEmpty()
   {
      $api = $this->api(array());
      $this->assertNull(poracle_track_delete_uids($api, '42', 'lure', array(), 1));
      $this->assertSame(array(), $this->calls);
   }

   public function testDeleteUidsJoinsWithCommas()
   {
      $api = $this->api(array(array(200, '{"deleted":[]}')));
      poracle_track_delete_uids($api, '42', 'lure', array(1, 3), 1);
      $this->assertSame('DELETE', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure?uid=1%2C3&profile=1', $this->calls[0]['url']);
   }

   public function testSetFieldPatchesTheListedUids()
   {
      $api = $this->api(array(array(200, '{"updated":[],"unchanged":[],"refused":[]}')));
      $rules = poracle_rules_in($this->snapshot, 'lure', 1);
      $this->assertSame(array(), poracle_track_set_field($api, '42', 'lure', $rules, 'distance', 750, 1));
      $this->assertSame('PATCH', $this->calls[0]['method']);
      $this->assertSame('http://p/api/v2/humans/42/tracking/lure?profile=1', $this->calls[0]['url']);
      $this->assertSame(array('uids' => array(1, 3), 'set' => array('distance' => 750)), $this->calls[0]['body']);
   }

   public function testSetFieldNullSwitchesTheSettingOff()
   {
      $api = $this->api(array(array(200, '{"updated":[],"unchanged":[],"refused":[]}'), array(200, '{"updated":[],"unchanged":[],"refused":[]}')));
      $rules = array(array('uid' => 3, 'profile_no' => 1, 'lure_id' => 0, 'clean' => true));
      poracle_track_set_field($api, '42', 'lure', $rules, 'clean', null, 1);
      poracle_track_set_field($api, '42', 'lure', $rules, 'distance', null, 1);
      $this->assertSame(array('clean' => false), $this->calls[0]['body']['set']);
      $this->assertSame(array('distance' => 0), $this->calls[1]['body']['set']);
   }

   public function testSetFieldReturnsRefusalsAndSkipsEmptyLists()
   {
      $refused = array('uid' => 3, 'reason' => 'conflict', 'detail' => 'would duplicate rule 1', 'conflicts_with' => 1);
      $api = $this->api(array(array(200, json_encode(array('updated' => array(), 'unchanged' => array(), 'refused' => array($refused))))));
      $this->assertSame(array(), poracle_track_set_field($api, '42', 'lure', array(), 'distance', 750, 1));
      $this->assertCount(0, $this->calls);
      $this->assertSame(array($refused), poracle_track_set_field($api, '42', 'lure', poracle_rules_in($this->snapshot, 'lure', 1), 'distance', 750, 1));
   }

   public function testRefusedMessageCountsRulesAndListsEachReasonOnce()
   {
      $refused = array(
         array('uid' => 2, 'reason' => 'conflict', 'detail' => 'would duplicate rule 1'),
         array('uid' => 4, 'reason' => 'conflict', 'detail' => 'would duplicate rule 1'),
         array('uid' => 9, 'reason' => 'not_found'),
      );
      $this->assertSame('3 alarm(s) were not changed: would duplicate rule 1; not_found', poracle_refused_message($refused));
   }

   public function testBulkDistanceSkipsRulesWithTheirOwnLocationOrAreas()
   {
      $rules = array(
         array('uid' => 1, 'lure_id' => 501),
         array('uid' => 2, 'lure_id' => 502, 'override_location_label' => 'home', 'distance' => 500),
         array('uid' => 3, 'lure_id' => 503, 'override_areas' => array('north')),
      );
      $this->assertSame(array(1, 3), array_column(poracle_distance_targets($rules, 0), 'uid'));
      $this->assertSame(array(1, 2), array_column(poracle_distance_targets($rules, 300), 'uid'));
      $api = $this->api(array(array(200, '{"updated":[],"unchanged":[],"refused":[]}')));
      poracle_track_set_field($api, '42', 'lure', $rules, 'distance', null, 1);
      $this->assertSame(array(1, 3), $this->calls[0]['body']['uids']);
      $this->assertSame(array(), poracle_track_set_field($api, '42', 'lure', array($rules[2]), 'distance', 300, 1));
      $this->assertCount(1, $this->calls);
   }

   public function testLocationBodyUsesLatitudeAndLongitude()
   {
      $this->assertSame(array('latitude' => 51.5, 'longitude' => -0.25), poracle_location_body('51.5', '-0.25'));
   }

   public function testProfileNumbersIncludeTheActiveProfileAndRuleProfiles()
   {
      $snap = array('human' => array('current_profile_no' => 1), 'profiles' => array(array('profile_no' => 3)),
         'tracking' => $this->snapshot['tracking']);
      $this->assertSame(array(1, 2, 3), poracle_profile_numbers($snap));
   }

   public function testMigrationProfilesRecreateAnActiveProfileWithNoEntry()
   {
      $hours = array(array('day' => 1, 'hours' => 9, 'mins' => 0));
      $snap = array(
         'human' => array('current_profile_no' => 2, 'area' => array('north'), 'latitude' => 1.5, 'longitude' => 2.5),
         'profiles' => array(array('profile_no' => 3, 'name' => 'Work', 'area' => array('south'), 'latitude' => 3.0, 'longitude' => 4.0, 'active_hours' => $hours)),
      );
      $this->assertSame(array(
         array('profile_no' => 2, 'name' => '', 'area' => array('north'), 'latitude' => 1.5, 'longitude' => 2.5, 'active_hours' => array()),
         array('profile_no' => 3, 'name' => 'Work', 'area' => array('south'), 'latitude' => 3.0, 'longitude' => 4.0, 'active_hours' => $hours),
      ), poracle_migration_profiles($snap));
      $snap['human']['current_profile_no'] = 3;
      $this->assertSame(array(3), array_column(poracle_migration_profiles($snap), 'profile_no'));
   }

   public function testHumanAndProfileView()
   {
      $snap = array(
         'human' => array('current_profile_no' => 1, 'area' => array('north'), 'latitude' => 1.5, 'longitude' => 2.5, 'enabled' => true, 'admin_disable' => false, 'disabled_date' => null, 'language' => ''),
         'profiles' => array(
            array('profile_no' => 1, 'name' => 'Home', 'area' => array('north'), 'latitude' => 1.5, 'longitude' => 2.5),
            array('profile_no' => 2, 'name' => 'Work', 'area' => array('south', 'east'), 'latitude' => 3.0, 'longitude' => 4.0),
         ),
      );
      $v = poracle_profile_view($snap, 2);
      $this->assertSame(array('Work', array('south', 'east'), 3.0, 4.0, 2, 1),
         array($v['name'], $v['area'], $v['latitude'], $v['longitude'], $v['profile_count'], $v['current_profile_no']));
      $this->assertSame(array(1, 0), array($v['enabled'], $v['admin_disable']));
      $this->assertSame(array('north'), poracle_profile_view($snap, 9)['area']);
      $this->assertNull(poracle_profile_view($snap, 9)['name']);
      $this->assertSame(array('pokemon'), poracle_type_for_table('monsters'));
      $this->assertSame(array('invasion', 'incident'), poracle_type_for_table('invasion'));
   }

   public function testPokemonGen()
   {
      $this->assertSame('all', poracle_pokemon_gen(null));
      $this->assertSame('1', poracle_pokemon_gen(151));
      $this->assertSame('2', poracle_pokemon_gen(152));
      $this->assertSame('9', poracle_pokemon_gen(1025));
   }

   public function testFilterByIds()
   {
      $rules = array(array('pokemon_id' => null), array('pokemon_id' => 25), array('pokemon_id' => 133));
      $this->assertCount(2, poracle_filter_by_ids($rules, array(0, 25)));
      $this->assertCount(3, poracle_filter_by_ids($rules, null));
   }

   public function testQuickPickMatch()
   {
      $this->assertTrue(poracle_quick_pick_match(array('pokemon_id' => null, 'min_iv' => 100), 'iv100'));
      $this->assertFalse(poracle_quick_pick_match(array('pokemon_id' => 25, 'min_iv' => 100), 'iv100'));
      $this->assertTrue(poracle_quick_pick_match(array('pokemon_id' => null, 'pvp_ranking_league' => 1500, 'pvp_ranking_worst' => 1), 'pvp1500'));
      $this->assertTrue(poracle_quick_pick_match(array('pokemon_id' => null, 'size' => 5, 'max_size' => null), 'xxl'));
      $this->assertFalse(poracle_quick_pick_match(array('pokemon_id' => null, 'size' => 5, 'max_size' => 5), 'xxs'));
   }
}
