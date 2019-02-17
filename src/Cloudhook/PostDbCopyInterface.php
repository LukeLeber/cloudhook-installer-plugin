<?php

namespace Drupal\cloudhooks\Cloudhook;

use Drupal\cloudhooks\CloudhookInterface;

/**
 * Interface PostDbCopyInterface.
 *
 * @package Drupal\cloudhooks\Cloudhook
 */
interface PostDbCopyInterface extends CloudhookInterface {

  /**
   * Fires on post-db-copy.
   *
   * @param string $application
   *   The application that the action has occurred on.
   * @param string $environment
   *   The environment that the action has occurred on.
   * @param string $database_name
   *   The name of the database that was copied.
   * @param string $source_environment
   *   The name of the environment that the database was copied from.
   */
  public function onPostDbCopy($application, $environment, $database_name, $source_environment);

}
