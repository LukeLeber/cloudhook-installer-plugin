<?php

namespace Drupal\cloudhooks;

/**
 * Interface for cloudhook plugins.
 */
interface CloudhookPluginInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

  public function description();

  public function getEvents();

}
