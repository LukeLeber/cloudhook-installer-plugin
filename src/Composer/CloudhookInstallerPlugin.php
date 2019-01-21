<?php

namespace lleber\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use lleber\Composer\Installer\CloudhookInstaller;

class CloudhookInstallerPlugin implements PluginInterface {

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    $installer = new CloudhookInstaller($io, $composer);
    $composer->getInstallationManager()->addInstaller($installer);
  }
}