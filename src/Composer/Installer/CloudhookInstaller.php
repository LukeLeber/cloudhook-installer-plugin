<?php

namespace Drupal\cloudhooks\Composer\Installer;

use Composer\Composer;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Drupal\cloudhooks\HookRepositoryInterface;

/**
 * Class CloudhookInstaller.
 *
 * @package Drupal\cloudhooks\Composer\Installer
 */
class CloudhookInstaller extends LibraryInstaller implements CloudhookInstallerInterface {

  /**
   * The type of plugin that is supported by this installer.
   *
   * @var string
   */
  const SUPPORTED_PLUGIN_TYPE = 'acquia-cloudhook';

  const EXTRA_KEY = 'cloud-hooks';

  /**
   * The path that all hooks must be installed to.
   *
   * @var string
   */
  const HOOK_PATH = 'hooks';

  const REQUIRED_KEYS = [
    'class',
    'events',
    'environments',
    'priority',
  ];

  const VALID_HOOK_TYPES = [
    'post-code-deploy',
    'post-code-update',
    'post-db-copy',
    'post-files-copy',
  ];

  /**
   * The hook repository service.
   *
   * @var \Drupal\cloudhooks\HookRepositoryInterface
   */
  protected $hookRepository;

  /**
   * CloudhookInstaller constructor.
   *
   * @param \Composer\IO\IOInterface $io
   *   The IO service.
   * @param \Composer\Composer $composer
   *   The composer service.
   * @param \Drupal\cloudhooks\HookRepositoryInterface $hook_repository
   *   The hook repository service.
   */
  public function __construct(IOInterface $io, Composer $composer, HookRepositoryInterface $hook_repository) {
    parent::__construct($io, $composer, 'library', NULL, NULL);
    $this->hookRepository = $hook_repository;
  }

  /**
   * {@inheritdoc}
   *
   * Always reinstall the files.
   */
  public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function supports($packageType) {
    return static::SUPPORTED_PLUGIN_TYPE === $packageType;
  }

  /**
   * Adds a number of hooks to the repository based on provided config.
   *
   * @param array $hook_config
   *   The hook configuration to install.
   *
   * @throws \InvalidArgumentException
   *   If the configuration array fails validation.
   */
  protected function installHook(array $hook_config) {
    $this->validate($hook_config);

    $class = $hook_config['class'];
    $events = $hook_config['events'];
    $environments = $hook_config['environments'];
    $priority = $hook_config['priority'];

    foreach ($events as $event) {
      foreach ($environments as $environment) {
        $this->hookRepository->register($class, $event, $environment, $priority);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
    parent::install($repo, $package);
    foreach ($this->getHooks($package) as $hook_config) {
      $this->installHook($hook_config);
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
  protected function getHooks(PackageInterface $package) {
    $hooks = [];

    $extra = $package->getExtra();
    if (array_key_exists(static::EXTRA_KEY, $extra)) {
      $hooks = $extra[static::EXTRA_KEY];
    }

    return $hooks;
  }

  /**
   * Ensures that the hook configuration array contains all required keys.
   *
   * @param array $hook_config
   *   The hook configuration to validate.
   *
   * @throws \InvalidArgumentException
   *   If the configuration array fails validation.
   */
  protected function validateRequiredKeys(array $hook_config) {
    foreach (static::REQUIRED_KEYS as $required_key) {
      if (!\array_key_exists($required_key, $hook_config)) {
        throw new \InvalidArgumentException(sprintf('Unable to install cloudhook.  Required configuration key "%s" is missing.', $required_key));
      }
    }
  }

  /**
   * Ensures that each event in the hook configuration array is valid.
   *
   * @param array $hook_config
   *   The hook configuration to validate.
   *
   * @throws \InvalidArgumentException
   *   If the configuration array fails validation.
   */
  protected function validateEvents(array $hook_config) {
    foreach ($hook_config['events'] as $event) {
      if (!in_array($event, static::VALID_HOOK_TYPES)) {
        throw new \InvalidArgumentException(sprintf('Unable to install cloudhook.  Event "%s" is not a recognized hook type.', $event));
      }
    }
  }

  /**
   * Validates the provided hook configuration array.
   *
   * @param array $hook_config
   *   The hook configuration to validate.
   *
   * @throws \InvalidArgumentException
   *   If the configuration array fails validation.
   */
  protected function validate(array $hook_config) {

    $this->validateRequiredKeys($hook_config);

    if (!\ctype_digit((string) $hook_config['priority'])) {
      throw new \InvalidArgumentException(sprintf('Unable to install cloudhook.  Priority "%s" is not an integer.', (string) $hook_config['priority']));
    }

    if (!is_array($hook_config['events'])) {
      throw new \InvalidArgumentException(sprintf('Unable to install cloudhook.  Events are not in an acceptable format.'));
    }

    $this->validateEvents($hook_config);

    if (!is_array($hook_config['environments'])) {
      throw new \InvalidArgumentException(sprintf('Unable to install cloudhook.  Environments are not in an acceptable format.'));
    }
  }

}
