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

  /* @var $hook_repository \Drupal\cloudhooks\HookRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
  public $hook_repository;

  /* @var $binaryInstaller \Composer\Installer\BinaryInstaller|\PHPUnit\Framework\MockObject\MockObject */
  public $binaryInstaller;

  public function __construct(IOInterface $io, Composer $composer, BinaryInstaller $binary_installer, HookRepositoryInterface $hook_repository) {
    $this->hook_repository = $hook_repository;
    $this->binaryInstaller = $binary_installer;
  }

  public function getHooks(PackageInterface $package) {
    return parent::getHooks($package);
  }

  public function validate(array $hook_config) {
    parent::validate($hook_config);
  }

  public function initializeVendorDir() {
    // no-op.
  }

  public function installCode(PackageInterface $package) {
    // no-op.
  }
}
