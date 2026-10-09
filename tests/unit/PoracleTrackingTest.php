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

   public function testSetFieldRepostsEveryRuleWithOneChange()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      $rules = poracle_rules_in($this->snapshot, 'lure', 1);
      poracle_track_set_field($api, '42', 'lure', $rules, 'distance', 750, 1);
      $this->assertSame(array(
         array('lure_id' => 501, 'distance' => 750, 'clean' => true),
         array('lure_id' => 0, 'distance' => 750, 'clean' => true),
      ), $this->calls[0]['body']);
   }

   public function testSetFieldNullMeansAny()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      poracle_track_set_field($api, '42', 'lure', array(array('uid' => 3, 'profile_no' => 1, 'lure_id' => 0, 'clean' => true)), 'clean', null, 1);
      $this->assertSame(array(array('lure_id' => 0)), $this->calls[0]['body']);
   }

   public function testHumanAndProfileView()
   {
      $snap = array(
         'human' => array('current_profile_no' => 1, 'area' => '["north"]', 'latitude' => 1.5, 'longitude' => 2.5, 'enabled' => 1, 'admin_disable' => 0, 'disabled_date' => null, 'language' => null),
         'profiles' => array(
            array('profile_no' => 1, 'name' => 'Home', 'area' => '["north"]', 'latitude' => 1.5, 'longitude' => 2.5),
            array('profile_no' => 2, 'name' => 'Work', 'area' => '["south","east"]', 'latitude' => 3.0, 'longitude' => 4.0),
         ),
      );
      $v = poracle_profile_view($snap, 2);
      $this->assertSame(array('Work', array('south', 'east'), 3.0, 4.0, 2, 1),
         array($v['name'], $v['area'], $v['latitude'], $v['longitude'], $v['profile_count'], $v['current_profile_no']));
      $this->assertSame(array('north'), poracle_profile_view($snap, 9)['area']);
      $this->assertNull(poracle_profile_view($snap, 9)['name']);
      $this->assertSame(array('pokemon'), poracle_type_for_table('monsters'));
      $this->assertSame(array('invasion', 'incident'), poracle_type_for_table('invasion'));
   }
}
