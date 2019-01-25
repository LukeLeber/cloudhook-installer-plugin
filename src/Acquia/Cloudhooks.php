<?php

namespace lleber\Acquia;

/**
 * Class Cloudhooks.
 *
 * @package lleber\Acquia
 */
class Cloudhooks {

  protected static $hooks;

  public static function register($hook, $event, $environment, $priority) {
    $hooks[] = [
      'hook' => $hook,
      'event' => $event,
      'environment' => $environment,
      'priority' => $priority,
    ];
  }

  public static function getHooks() {
    return static::$hooks;
  }
}