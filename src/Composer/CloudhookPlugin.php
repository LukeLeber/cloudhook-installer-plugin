<?php

namespace lleber\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use lleber\Acquia\Cloudhooks;
use lleber\Composer\Installer\CloudhookInstaller;

class CloudhookPlugin implements EventSubscriberInterface, PluginInterface {

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    $installer = new CloudhookInstaller($io, $composer);
    $composer->getInstallationManager()->addInstaller($installer);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ScriptEvents::POST_INSTALL_CMD => 'installHooks',
      ScriptEvents::POST_UPDATE_CMD => 'installHooks',
    ];
  }

  /**
   * Installs all registered hooks to the proper directory.
   *
   * @param \Composer\Script\Event $event
   *   The script event that triggered this callback.
   */
  public static function installHooks(Event $event) {

    $hooks = Cloudhooks::getHooks();
    foreach($hooks as $hook_config) {
      $hook = $hook_config['hook'];
      $event = $hook_config['event'];
      $environment = $hook_config['environment'];
      $priority = $hook_config['priority'];

      echo "Installing {$hook} on {$environment} that fires on {$event} in {$priority} order.";
    }
  }
}
