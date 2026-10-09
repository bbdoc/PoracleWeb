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
