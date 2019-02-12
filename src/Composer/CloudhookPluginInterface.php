<?php

namespace Drupal\cloudhooks\Composer;

use Composer\Script\Event;

/**
 * Interface CloudhookPluginInterface.
 *
 * @package Drupal\cloudhooks\Composer
 */
interface CloudhookPluginInterface {

  /**
   * Installs all registered hooks to the proper directory.
   *
   * @param \Composer\Script\Event $event
   *   The script event that triggered this callback.
   */
  public function installHooks(Event $event);

}
