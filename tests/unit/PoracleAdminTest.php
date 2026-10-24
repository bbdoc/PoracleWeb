<?php
use PHPUnit\Framework\TestCase;

class PoracleAdminTest extends TestCase
{
   private function delegated()
   {
      return array('delegated_id' => '1', 'delegated_channels' => array(
         'discord' => array('channels' => array('100'), 'webhooks' => array('hook-a')),
         'telegram' => array('channels' => array('-200')),
      ));
   }

   public function testSuperAdminMayManageAnyone()
   {
      $this->assertTrue(admin_may_manage(array('admin_id' => '1'), array('id' => '9', 'type' => 'discord:user', 'name' => 'x')));
   }

   public function testDelegatedChannelAdminScopedToTheirChannels()
   {
      $s = $this->delegated();
      $this->assertTrue(admin_may_manage($s, array('id' => '100', 'type' => 'discord:channel', 'name' => 'c')));
      $this->assertFalse(admin_may_manage($s, array('id' => '101', 'type' => 'discord:channel', 'name' => 'c')));
      $this->assertTrue(admin_may_manage($s, array('id' => 'w', 'type' => 'webhook', 'name' => 'hook-a')));
      $this->assertTrue(admin_may_manage($s, array('id' => '-200', 'type' => 'telegram:group', 'name' => 'g')));
      $this->assertFalse(admin_may_manage($s, array('id' => '9', 'type' => 'discord:user', 'name' => 'u')));
   }

   public function testUsersAdminMayManageUsersOnly()
   {
      $s = array('users_admin' => 'True', 'delegated_id' => '1');
      $this->assertTrue(admin_may_manage($s, array('id' => '9', 'type' => 'telegram:user', 'name' => 'u')));
      $this->assertFalse(admin_may_manage($s, array('id' => '100', 'type' => 'discord:channel', 'name' => 'c')));
   }

   public function testMaySwitchBackToSelf()
   {
      $this->assertTrue(admin_may_manage($this->delegated(), array('id' => '1', 'type' => 'discord:user', 'name' => 'me')));
   }

   public function testTelegramAuth()
   {
      $token = 'bot:token';
      $params = array('id' => '5', 'username' => 'u', 'auth_date' => '1000');
      $check = array();
      foreach ($params as $k => $v) { $check[] = "$k=$v"; }
      sort($check);
      $params['hash'] = hash_hmac('sha256', implode("\n", $check), hash('sha256', $token, true));
      $this->assertTrue(telegram_auth_valid($params, $token, 1500));
      $this->assertFalse(telegram_auth_valid($params, $token, 1000 + 86401));
      $params['id'] = '6';
      $this->assertFalse(telegram_auth_valid($params, $token, 1500));
   }

   public function testTelegramAuthRejectsEmptyBotToken()
   {
      $params = array('id' => '5', 'auth_date' => '1000');
      $params['hash'] = hash_hmac('sha256', "auth_date=1000\nid=5", hash('sha256', '', true));
      $this->assertFalse(telegram_auth_valid($params, '', 1500));
   }

   public function testTelegramAuthIgnoresNonWidgetParams()
   {
      $token = 'bot:token';
      $params = array('id' => '5', 'first_name' => 'A', 'auth_date' => '1000');
      $params['hash'] = hash_hmac('sha256', "auth_date=1000\nfirst_name=A\nid=5", hash('sha256', $token, true));
      $params['type'] = 'display';
      $params['page'] = 'migrate';
      $this->assertTrue(telegram_auth_valid($params, $token, 1500));
      $params['first_name'] = 'B';
      $this->assertFalse(telegram_auth_valid($params, $token, 1500));
   }
}
