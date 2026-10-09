<?php
use PHPUnit\Framework\TestCase;

class PoracleApiTest extends TestCase
{
   private $calls = array();

   private function api(array $responses)
   {
      $calls = &$this->calls;
      return new PoracleApi('http://poracle:3030/', 's3cret', function ($method, $url, $headers, $body) use (&$calls, &$responses) {
         $calls[] = compact('method', 'url', 'headers', 'body');
         return array_shift($responses);
      });
   }

   public function testGetSendsSecretAndDecodes()
   {
      $api = $this->api(array(array(200, '{"rules":[]}')));
      $this->assertSame(array('rules' => array()), $api->get('/api/v2/humans/1/tracking/lure'));
      $this->assertSame('GET', $this->calls[0]['method']);
      $this->assertSame('http://poracle:3030/api/v2/humans/1/tracking/lure', $this->calls[0]['url']);
      $this->assertContains('X-Poracle-Secret: s3cret', $this->calls[0]['headers']);
   }

   public function testBooleansInQueryAreTrueFalse()
   {
      $this->assertSame('all_profiles=true&silent=false&profile=2', PoracleApi::buildQuery(array('all_profiles' => true, 'silent' => false, 'profile' => 2)));
   }

   public function testPostEncodesJsonBody()
   {
      $api = $this->api(array(array(200, '{"created":[],"updated":[],"unchanged":[]}')));
      $api->post('/api/v2/humans/1/tracking/lure', array(array('lure_id' => 501)), array('profile' => 1));
      $this->assertSame('[{"lure_id":501}]', $this->calls[0]['body']);
      $this->assertContains('Content-Type: application/json', $this->calls[0]['headers']);
      $this->assertStringEndsWith('?profile=1', $this->calls[0]['url']);
   }

   public function testProblemJsonBecomesException()
   {
      $api = $this->api(array(array(422, '{"title":"Unprocessable Entity","status":422,"detail":"validation failed","errors":[{"message":"expected integer","location":"body[0].lure_id","value":"x"}]}')));
      try {
         $api->post('/api/v2/humans/1/tracking/lure', array(array('lure_id' => 'x')));
         $this->fail('expected exception');
      } catch (PoracleApiException $e) {
         $this->assertSame(422, $e->status);
         $this->assertSame('validation failed (body[0].lure_id: expected integer)', $e->userMessage());
      }
   }

   public function testProblemWithoutDetailUsesTitle()
   {
      $api = $this->api(array(array(404, '{"title":"Not Found","status":404}')));
      $this->expectException(PoracleApiException::class);
      $this->expectExceptionMessage('Not Found');
      $api->get('/api/v2/humans/nobody');
   }

   public function testV1ErrorShape()
   {
      $api = $this->api(array(array(400, '{"status":"error","message":"User not found"}')));
      try {
         $api->get('/api/config/poracleWeb');
         $this->fail('expected exception');
      } catch (PoracleApiException $e) {
         $this->assertSame('User not found', $e->userMessage());
      }
   }

   public function testTransportFailureIsStatusZero()
   {
      $api = $this->api(array(array(0, null)));
      try {
         $api->get('/health');
         $this->fail('expected exception');
      } catch (PoracleApiException $e) {
         $this->assertSame(0, $e->status);
         $this->assertSame('Poracle unavailable', $e->userMessage());
      }
   }

   public function testEmptyBodyReturnsNull()
   {
      $api = $this->api(array(array(204, '')));
      $this->assertNull($api->delete('/api/v2/humans/1/location'));
   }
}
