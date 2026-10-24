<?php
use PHPUnit\Framework\TestCase;

// Opt-in: needs PORACLE_TEST_API, PORACLE_TEST_SECRET, PORACLE_TEST_HUMAN.
class PoracleLiveTest extends TestCase
{
   private $api;
   private $human;

   protected function setUp(): void
   {
      if (!getenv('PORACLE_TEST_API')) {
         $this->markTestSkipped('PORACLE_TEST_API not set');
      }
      $this->api = new PoracleApi(getenv('PORACLE_TEST_API'), getenv('PORACLE_TEST_SECRET'));
      $this->human = getenv('PORACLE_TEST_HUMAN');
      foreach (array('pokemon', 'lure') as $type) {
         $rules = $this->api->get(poracle_tracking_path($this->human, $type), array('profile' => 1))['rules'];
         poracle_track_delete_uids($this->api, $this->human, $type, array_column($rules, 'uid'), 1);
      }
   }

   private function rules($type)
   {
      $snap = $this->api->get(poracle_human_path($this->human) . '/tracking', array('all_profiles' => true));
      return poracle_rules_in($snap, $type, 1);
   }

   public function testHealthHasCapabilities()
   {
      $this->assertTrue(poracle_supports_v2($this->api->get('/health')));
   }

   public function testAddEditKeepsPingAndEditFlag()
   {
      poracle_track_add($this->api, $this->human, 'lure', array(array('lure_id' => 501, 'ping' => '@here', 'edit' => true)), 1);
      $rule = $this->rules('lure')[0];
      poracle_track_update($this->api, $this->human, 'lure', $rule['uid'], array('distance' => 300), 1);
      $after = $this->rules('lure');
      $this->assertCount(1, $after);
      $this->assertSame('@here', $after[0]['ping']);
      $this->assertTrue($after[0]['edit']);
      $this->assertSame(300, $after[0]['distance']);
   }

   public function testSetFieldOnLegacyAliasRowsCreatesNoDuplicates()
   {
      // Simulates rows the old PoracleWeb wrote (pvp best/worst stored as 0) by
      // posting the explicit values v2 would otherwise read back as null.
      poracle_track_add($this->api, $this->human, 'pokemon', array(array('pokemon_id' => 25, 'min_iv' => 0), array('pokemon_id' => 133)), 1);
      $before = $this->rules('pokemon');
      poracle_track_set_field($this->api, $this->human, 'pokemon', $before, 'distance', 400, 1);
      poracle_track_set_field($this->api, $this->human, 'pokemon', $this->rules('pokemon'), 'clean', true, 1);
      $after = $this->rules('pokemon');
      $this->assertCount(2, $after);
      $this->assertSame(array_column($before, 'uid'), array_column($after, 'uid'));
      $this->assertSame(array(400, 400), array_column($after, 'distance'));
      $this->assertSame(array(true, true), array_column($after, 'clean'));
   }

   public function testSetFieldRefusesARuleItWouldDuplicate()
   {
      poracle_track_add($this->api, $this->human, 'lure', array(array('lure_id' => 501, 'distance' => 100), array('lure_id' => 501, 'distance' => 200)), 1);
      $before = $this->rules('lure');
      $this->assertCount(2, $before);
      $refused = poracle_track_set_field($this->api, $this->human, 'lure', $before, 'distance', 300, 1);
      $this->assertCount(1, $refused);
      $this->assertSame('conflict', $refused[0]['reason']);
      $this->assertEqualsCanonicalizing(array(200, 300), array_column($this->rules('lure'), 'distance'));
   }

   public function testLocationSetAndClear()
   {
      $path = poracle_human_path($this->human);
      $this->api->post($path . '/location', poracle_location_body(51.5, -0.25), array('profile' => 1));
      $human = $this->api->get($path . '/tracking')['human'];
      $this->assertEquals(array(51.5, -0.25), array($human['latitude'], $human['longitude']));
      $this->api->delete($path . '/location', array('profile' => 1));
      $human = $this->api->get($path . '/tracking')['human'];
      $this->assertEquals(array(0, 0), array($human['latitude'], $human['longitude']));
   }

   public function testSavedLocationTakesLatitudeAndLongitude()
   {
      $path = poracle_human_path($this->human);
      try { $this->api->delete($path . '/locations/pweb-it'); } catch (PoracleApiException $e) {}
      $this->api->post($path . '/locations', array('label' => 'pweb-it') + poracle_location_body(51.5, -0.25));
      $named = array_column($this->api->get($path . '/locations')['locations']['named'], null, 'label');
      $this->assertEquals(51.5, $named['pweb-it']['latitude']);
      $this->api->delete($path . '/locations/pweb-it');
   }

   public function testHumanShapeIsTyped()
   {
      $human = $this->api->get(poracle_human_path($this->human))['human'];
      $this->assertIsBool($human['enabled']);
      $this->assertIsArray($human['area']);
      $this->assertIsString($human['language']);
   }

   public function testProblemJsonOnBadBody()
   {
      try {
         poracle_track_add($this->api, $this->human, 'lure', array(array('lure_id' => 'x')), 1);
         $this->fail('expected 422');
      } catch (PoracleApiException $e) {
         $this->assertSame(422, $e->status);
         $this->assertNotSame('', $e->userMessage());
      }
   }
}
