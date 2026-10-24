<?php
use PHPUnit\Framework\TestCase;

class PoracleSessionLogicTest extends TestCase
{
   private function fakeApi($status, $body)
   {
      return new PoracleApi('http://x', 's', function () use ($status, $body) { return array($status, $body); });
   }

   public function testSupportsV2NeedsCapabilities()
   {
      $this->assertTrue(poracle_supports_v2(array('status' => 'ok', 'capabilities' => array('buttons' => true))));
      $this->assertFalse(poracle_supports_v2(array('status' => 'ok')));
      $this->assertFalse(poracle_supports_v2(null));
   }

   public function testHumanPathEncodes()
   {
      $this->assertSame('/api/v2/humans/a%2Fb', poracle_human_path('a/b'));
   }

   public function testFindHumanInstanceSkips404()
   {
      $apis = array(
         'poracle_a' => $this->fakeApi(404, '{"title":"Not Found","status":404,"detail":"human not found"}'),
         'poracle_b' => $this->fakeApi(200, '{"human":{"id":"42"}}'),
      );
      $this->assertSame('poracle_b', poracle_find_human_instance($apis, '42'));
   }

   public function testFindHumanInstanceNullWhenNowhere()
   {
      $apis = array('poracle_a' => $this->fakeApi(404, '{"title":"Not Found","status":404}'));
      $this->assertNull(poracle_find_human_instance($apis, '42'));
   }

   public function testFindHumanInstanceRethrowsOtherErrors()
   {
      $this->expectException(PoracleApiException::class);
      poracle_find_human_instance(array('a' => $this->fakeApi(0, null)), '42');
   }

   public function testFindHumanInstanceSkipsBrokenInstance()
   {
      $apis = array(
         'a' => $this->fakeApi(0, null),
         'b' => $this->fakeApi(200, '{"human":{"id":"42"}}'),
      );
      $this->assertSame('b', poracle_find_human_instance($apis, '42'));
   }
}
