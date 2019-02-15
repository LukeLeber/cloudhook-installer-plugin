<?php

namespace Drupal\Tests\cloudhooks\Proxy\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Drupal\cloudhooks\HookRepository;
use Drupal\cloudhooks\Composer\CloudhookPlugin;
use Drupal\cloudhooks\Composer\Installer\CloudhookInstaller;

/**
 * Class CloudhookPluginProxy.
 *
 * @package lleber\Tests\Proxy\Composer
 */
class CloudhookPluginProxy extends CloudhookPlugin {

  /* @noinspection PhpMissingParentConstructorInspection */

  /**
   * CloudhookPluginProxy constructor.
   *
   * @param \Drupal\cloudhooks\HookRepository $hook_repository
   *   A mocked hook repository object.
   * @param \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller $installer
   *   A mocked installer object.
   */
  public function __construct(HookRepository $hook_repository, CloudhookInstaller $installer) {
    $this->hookRepository = $hook_repository;
    $this->installer = $installer;
  }

  /**
   * {@inheritdoc}
   *
   * @return \Drupal\cloudhooks\HookRepository|\PHPUnit\Framework\MockObject\MockObject
   *   A mocked version of the hook repository.
   */
  public function getHookRepository() {
    return $this->hookRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function getInstaller(Composer $composer, IOInterface $io) {
    return $this->installer;
  }

}
