<?php

namespace Drupal\cloudhooks;

/**
 * Class Cloudhooks.
 *
 * @package lleber\Acquia
 */
class HookRepository implements HookRepositoryInterface {

  /**
   * The currently registered hooks.
   *
   * @var array
   */
  protected $hooks;

  /**
   * {@inheritdoc}
   */
  public function register($class, $event, $environment, $priority) {
    $this->hooks[] = [
      'class' => $class,
      'event' => $event,
      'environment' => $environment,
      'priority' => $priority,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getHooks() {
    return $this->hooks;
  }

}
