<?php
// Library files under test must only define functions/classes (no side effects on include).
require_once __DIR__ . '/../vendor/autoload.php';
foreach (array('poracle_api.php', 'poracle_tracking.php', 'poracle_forms.php', 'poracle_admin.php') as $f) {
   $path = __DIR__ . '/../include/' . $f;
   if (file_exists($path)) {
      require_once $path;
   }
}
