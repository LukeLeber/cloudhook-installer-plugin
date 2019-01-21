<?php

namespace Drupal\cloudhooks;

/**
 * Interface HookRepositoryInterface.
 *
 * @package Drupal\cloudhooks
 */
interface HookRepositoryInterface {

  /**
   * Registers the provided hook configuration with the repository.
   *
   * @param string $class
   *   The hook class.
   * @param string $event
   *   The hook event.
   * @param string $environment
   *   The hook environment.
   * @param int $priority
   *   The hook priority.
   */
  public function register($class, $event, $environment, $priority);

  /**
   * Retrieves the currently registered hook configuration.
   *
   * @return array
   *   The currently registered hook configuration.
   */
  public function getHooks();

}
