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
}
