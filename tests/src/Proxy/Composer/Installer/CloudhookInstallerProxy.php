<?php

namespace Drupal\Tests\cloudhooks\Proxy\Composer\Installer;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Drupal\cloudhooks\Composer\Installer\CloudhookInstaller;
use Drupal\cloudhooks\HookRepositoryInterface;

/**
 * Class CloudhookInstallerProxy.
 *
 * @package lleber\Tests\Proxy\Composer\Installer
 */
class CloudhookInstallerProxy extends CloudhookInstaller {

  /**
   * Mocked hook repository service.
   *
   * @var \Drupal\cloudhooks\HookRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  public $hookRepository;

  /**
   * Mocked binary installer service.
   *
   * @var \Composer\Installer\BinaryInstaller|\PHPUnit\Framework\MockObject\MockObject
   */
  public $binaryInstaller;

  /* @noinspection PhpMissingParentConstructorInspection */

  /**
   * CloudhookInstallerProxy constructor.
   *
   * @param \Composer\IO\IOInterface $io
   *   The mocked io service.
   * @param \Composer\Composer $composer
   *   The mocked composer servie.
   * @param \Composer\Installer\BinaryInstaller $binary_installer
   *   The mocked binary installer service.
   * @param \Drupal\cloudhooks\HookRepositoryInterface $hook_repository
   *   The mocked hook repository service.
   */
  public function __construct(IOInterface $io, Composer $composer, BinaryInstaller $binary_installer, HookRepositoryInterface $hook_repository) {
    $this->hookRepository = $hook_repository;
    $this->binaryInstaller = $binary_installer;
  }

  /**
   * {@inheritdoc}
   *
   * Made public for testing purposes.
   */
  public function getHooksProxy(PackageInterface $package) {
    return parent::getHooks($package);
  }

  /**
   * {@inheritdoc}
   *
   * Made public for testing purposes.
   */
  public function validateProxy(array $hook_config) {
    parent::validate($hook_config);
  }

  /**
   * {@inheritdoc}
   */
  public function initializeVendorDir() {
    // no-op.
  }

  /**
   * {@inheritdoc}
   */
  public function installCode(PackageInterface $package) {
    // no-op.
  }

}
