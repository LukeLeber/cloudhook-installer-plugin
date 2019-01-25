<?php

$vendor_autoload = join(DIRECTORY_SEPARATOR, ['vendor', 'autoload.php']);

if (!is_readable($vendor_autoload)) {
  die('Dependencies not found.  Please run "composer install" to continue.');
}

/* @noinspection PhpIncludeInspection */
require_once $vendor_autoload;
