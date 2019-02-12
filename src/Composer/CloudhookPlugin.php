<?php

namespace Drupal\cloudhooks\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Drupal\cloudhooks\HookRepository;
use Drupal\cloudhooks\Composer\Installer\CloudhookInstaller;

/**
 * Class CloudhookPlugin.
 *
 * @package lleber\Composer
 */
class CloudhookPlugin implements CloudhookPluginInterface, EventSubscriberInterface, PluginInterface {

  /**
   * The relative filesystem path that hooks are installed to.
   *
   * @var string
   */
  const HOOK_INSTALL_DIR = './hooks';

  /**
   * The hook repository service.
   *
   * @var \Drupal\cloudhooks\HookRepository
   */
  protected $hook_repository;

  /**
   * The installer service.
   *
   * @var \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller
   */
  protected $installer;

  /**
   * CloudhookPlugin constructor.
   */
  public function __construct() {
    $this->hook_repository = new HookRepository();
  }

  /**
   * Retrieves the installer service.
   *
   * @TODO: This is not testable.
   *
   * @param \Composer\Composer $composer
   *   The composer instance.
   * @param \Composer\IO\IOInterface $io
   *   The IO instance.
   *
   * @return \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller
   *   The installer service.
   */
  protected function getInstaller(Composer $composer, IOInterface $io) {
    if (!$this->installer) {
      $this->installer = new CloudhookInstaller($io, $composer, $this->hook_repository);
    }
    return $this->installer;
  }

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    $installer = $this->getInstaller($composer, $io);
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
   * {@inheritdoc}
   */
  public function installHooks(Event $event) {
    $this->hook_repository->getHooks();
  }

}
