<?php

// Whether the logged-in admin may act as $target (array with id, type, name).
function admin_may_manage(array $session, array $target)
{
   if (!empty($session['admin_id']) || !empty($session['poracle_admin'])) {
      return true;
   }
   if (isset($session['delegated_id']) && $target['id'] === $session['delegated_id']) {
      return true;
   }
   $type = $target['type'];
   if (substr($type, -5) === ':user') {
      return !empty($session['users_admin']);
   }
   $d = isset($session['delegated_channels']) ? $session['delegated_channels'] : array();
   $list = function ($platform, $key) use ($d) {
      return isset($d[$platform][$key]) ? array_map('strval', (array) $d[$platform][$key]) : array();
   };
   if ($type === 'discord:channel') {
      return in_array((string) $target['id'], $list('discord', 'channels'), true);
   }
   if ($type === 'webhook') {
      return in_array((string) $target['name'], $list('discord', 'webhooks'), true);
   }
   if ($type === 'telegram:channel' || $type === 'telegram:group') {
      return in_array((string) $target['id'], $list('telegram', 'channels'), true);
   }
   return false;
}

// The Telegram login widget's signature check (HMAC-SHA256 of the sorted
// key=value lines, keyed with SHA256 of the bot token), plus a one-day expiry.
function telegram_auth_valid(array $params, $botToken, $now)
{
   // No token configured: anyone could sign with the empty key.
   if ((string) $botToken === '' || !isset($params['hash'], $params['auth_date']) || !is_string($params['hash'])) {
      return false;
   }
   $hash = $params['hash'];
   // Only the widget's own fields are signed; the redirect URL may add
   // others (e.g. type/page on the migrate page).
   $fields = array('id', 'first_name', 'last_name', 'username', 'photo_url', 'auth_date');
   $params = array_intersect_key($params, array_flip($fields));
   $check = array();
   foreach ($params as $key => $value) {
      if (!is_scalar($value)) {
         return false;
      }
      $check[] = $key . '=' . $value;
   }
   sort($check);
   $expected = hash_hmac('sha256', implode("\n", $check), hash('sha256', (string) $botToken, true));
   return hash_equals($expected, $hash) && ($now - (int) $params['auth_date']) <= 86400;
}
