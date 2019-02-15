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

  /**
   * CloudhookInstallerProxy constructor.
   *
   * @param \Composer\IO\IOInterface $io
   * @param \Composer\Composer $composer
   * @param \Composer\Installer\BinaryInstaller $binary_installer
   * @param \Drupal\cloudhooks\HookRepositoryInterface $hook_repository
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
  public function getHooks(PackageInterface $package) {
    return parent::getHooks($package);
  }

  /**
   * {@inheritdoc}
   *
   * Made public for testing purposes.
   */
  public function validate(array $hook_config) {
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
