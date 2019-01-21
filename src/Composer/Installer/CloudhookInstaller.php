<?php

namespace lleber\Composer\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class CloudhookInstaller extends LibraryInstaller {

  /**
   * The path that all hooks must be installed to.
   *
   * @var string
   */
  const HOOK_PATH = 'hook';

  /**
   * {@inheritDoc}
   */
  public function supports($packageType) {
    return 'lleber-cloudhook' === $packageType;
  }

  /**
   * {@inheritDoc}
   */
  public function getInstallPath(PackageInterface $package) {
    if(strpos($package->getPrettyName(), 'lleber/cloudhook-') !== 0) {
      throw new \InvalidArgumentException(
        'Unable to install cloudhook, cloudhook packages ' .
        'should always start their package name with ' .
        '"lleber/cloudhook-"'
      );
    }

    return static::HOOK_PATH;
  }
}
