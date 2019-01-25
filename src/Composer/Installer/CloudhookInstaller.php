<?php

namespace lleber\Composer\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use lleber\Acquia\Cloudhooks;

class CloudhookInstaller extends LibraryInstaller {

  const EXTRA_KEY = 'acquia-cloud-hooks';

  /**
   * The path that all hooks must be installed to.
   *
   * @var string
   */
  const HOOK_PATH = 'hooks';

  /**
   * {@inheritDoc}
   */
  public function supports($packageType) {
    return 'acquia-cloudhook' === $packageType;
  }

  /**
   * {@inheritDoc}
   */
  public function getInstallPath(PackageInterface $package) {
    if($package->getPrettyName() !== 'acquia/cloudhook') {
      throw new \InvalidArgumentException(
        'Unable to install cloudhook, cloudhook packages ' .
        'should always start their package name with ' .
        '"acquia/cloudhook-"'
      );
    }

    return static::HOOK_PATH;
  }

  /**
   * {@inheritdoc}
   */
  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    parent::install($repo, $package);

    foreach($this->getCloudhooks($package) as $hook => $hook_config) {
      $events = $hook_config['events'];
      $environments = $hook_config['environments'];
      $priority = $hook_config['priority'];

      foreach($events as $event) {
        foreach($environments as $environment) {
          Cloudhooks::register($hook, $event, $environment, $priority);
        }
      }
    }
  }

  /**
   * Retrieves an array of all cloudhooks provided by the package.
   *
   * @param \Composer\Package\PackageInterface $package
   *   The package that is being installed.
   *
   * @return array
   *   The cloudhooks provided by the package.
   */
  protected function getCloudhooks(PackageInterface $package) {
    $hooks = [];

    $extra = $package->getExtra();
    if (array_key_exists(static::EXTRA_KEY, $extra)) {
      $hooks = $extra[static::EXTRA_KEY];
    }

    return $hooks;
  }
}
