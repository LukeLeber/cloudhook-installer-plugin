<?php

namespace Drupal\cloudhooks\Cloudhook;

use Drupal\cloudhooks\CloudhookInterface;

/**
 * Interface PostFilesCopyInterface.
 *
 * @package Drupal\cloudhooks\Cloudhook
 */
interface PostFilesCopyInterface extends CloudhookInterface {

  /**
   * Fires on post-files-copy.
   *
   * @param string $application
   *   The application that the action has occurred on.
   * @param string $environment
   *   The environment that the action has occurred on.
   * @param string $source_environment
   *   The name of the environment that the files were copied from.
   */
  public function onPostFilesCopy($application, $environment, $source_environment);

}
