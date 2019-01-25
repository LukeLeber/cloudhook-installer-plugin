<?php

$autoload_file = join(
  DIRECTORY_SEPARATOR, [
    dirname(dirname(__FILE__)),
    'autoload.php'
  ]
);

/* @noinspection PhpIncludeInspection */
require_once $autoload_file;
