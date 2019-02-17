<?php

namespace Drupal\Tests\cloudhooks\Unit\Composer\Installer;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\BinaryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Drupal\cloudhooks\Composer\Installer\CloudhookInstaller;
use Drupal\cloudhooks\HookRepositoryInterface;
use Drupal\Tests\cloudhooks\Proxy\Composer\Installer\CloudhookInstallerProxy;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudhookInstallerTest.
 *
 * @package Drupal\Tests\cloudhooks\Unit\Composer\Installer
 */
class CloudhookInstallerTest extends TestCase {

  /**
   * An instance of the class under test.
   *
   * @var \Drupal\Tests\cloudhooks\Proxy\Composer\Installer\CloudhookInstallerProxy
   */
  protected $installer;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {

    /* @var $composer_mock \Composer\Composer|\PHPUnit\Framework\MockObject\MockObject */
    $composer_mock = $this->getMockBuilder(Composer::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $io_mock \Composer\IO\IOInterface|\PHPUnit\Framework\MockObject\MockObject */
    $io_mock = $this->getMockBuilder(IOInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $hook_repo_mock \Drupal\cloudhooks\HookRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    $hook_repo_mock = $this->getMockBuilder(HookRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $binary_installer_mock \Composer\Installer\BinaryInstaller|\PHPUnit\Framework\MockObject\MockObject */
    $binary_installer_mock = $this->getMockBuilder(BinaryInstaller::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->installer = new CloudhookInstallerProxy(
      $io_mock,
      $composer_mock,
      $binary_installer_mock,
      $hook_repo_mock
    );
  }

  /**
   * Test case for the ::__construct method.
   *
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::__construct
   */
  public function testConstruct() {

    /* @var $config_mock \Composer\Config|\PHPUnit\Framework\MockObject\MockObject */
    $config_mock = $this->getMockBuilder(Config::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $composer_mock \Composer\Composer|\PHPUnit\Framework\MockObject\MockObject */
    $composer_mock = $this->getMockBuilder(Composer::class)
      ->disableOriginalConstructor()
      ->getMock();

    $composer_mock
      ->method('getConfig')
      ->willReturn($config_mock);

    /* @var $io_mock \Composer\IO\IOInterface|\PHPUnit\Framework\MockObject\MockObject */
    $io_mock = $this->getMockBuilder(IOInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $hook_repo_mock \Drupal\cloudhooks\HookRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    $hook_repo_mock = $this->getMockBuilder(HookRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    new CloudhookInstaller($io_mock, $composer_mock, $hook_repo_mock);

    static::assertTrue(TRUE);
  }

  /**
   * Test case for the ::isInstalled method.
   *
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::isInstalled
   */
  public function testIsInstalled() {

    /* @var $repo_mock \Composer\Repository\InstalledRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    $repo_mock = $this->getMockBuilder(InstalledRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $package_mock \Composer\Package\PackageInterface|\PHPUnit\Framework\MockObject\MockObject */
    $package_mock = $this->getMockBuilder(PackageInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    static::assertFalse($this->installer->isInstalled($repo_mock, $package_mock));
  }

  /**
   * Test case for the ::supports method.
   *
   * @param array $data
   *   The sample data passed in from the provider.
   *
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::supports
   *
   * @dataProvider supportsProvider
   */
  public function testSupports(array $data) {
    $actual = $this->installer->supports($data['package']);
    static::assertEquals($data['expected'], $actual);
  }

  /**
   * Data provider for ::testSupports.
   *
   * @return array
   *   Sample data.
   */
  public function supportsProvider() {
    return [
      'true' => [
        [
          'expected' => TRUE,
          'package' => 'acquia-cloudhook',
        ],
      ],
      'false' => [
        [
          'expected' => FALSE,
          'package' => 'not-acquia-cloudhook',
        ],
      ],
    ];
  }

  /**
   * Test case for the ::install method.
   *
   * @param array $extra
   *   The sample data passed in from the provider.
   *
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::install
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::installHook
   *
   * @dataProvider installProvider
   */
  public function testInstall(array $extra) {

    /* @var $repo_mock \Composer\Repository\InstalledRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    $repo_mock = $this->getMockBuilder(InstalledRepositoryInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $package_mock \Composer\Package\PackageInterface|\PHPUnit\Framework\MockObject\MockObject */
    $package_mock = $this->getMockBuilder(PackageInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $package_mock->expects(static::once())
      ->method('getExtra')
      ->willReturn($extra);

    $this->installer->hookRepository
      ->expects(self::exactly(12))
      ->method('register');

    $this->installer->install($repo_mock, $package_mock);
  }

  /**
   * Data provider for ::testGetHooks.
   *
   * @return array
   *   Sample data.
   */
  public function installProvider() {
    return [
      [
        [
          'cloud-hooks' => [
            [
              'class' => 'Drupal\\cloudhooks\\Dummy',
              'events' => [
                'post-code-deploy',
                'post-code-update',
                'post-db-copy',
                'post-files-copy',
              ],
              'environments' => [
                'dev',
                'test',
                'prod',
              ],
              'priority' => 0,
            ],
          ],
        ],
      ],
    ];
  }

  /**
   * Test case for the ::getHooks method.
   *
   * @param array $extra
   *   The sample data passed in from the provider.
   *
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::getHooks
   *
   * @dataProvider extraProvider
   */
  public function testGetHooks(array $extra) {

    $is_relevant = $extra['relevant'];
    unset($extra['relevant']);

    /* @var $package_mock \Composer\Package\PackageInterface|\PHPUnit\Framework\MockObject\MockObject */
    $package_mock = $this->getMockBuilder(PackageInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $package_mock->expects(static::once())
      ->method('getExtra')
      ->willReturn($extra);

    $hooks = $this->installer->getHooksProxy($package_mock);
    if ($is_relevant) {
      static::assertSame($extra['cloud-hooks'], $hooks);
    }
    else {
      static::assertEmpty($hooks);
    }
  }

  /**
   * Data provider for ::testGetHooks.
   *
   * @return array
   *   Sample data.
   */
  public function extraProvider() {
    return [
      'relevant' => [
        [
          'relevant' => TRUE,
          'cloud-hooks' => [
            'class' => 'Drupal\\cloudhooks\\Dummy',
            'events' => [
              'post-code-deploy',
              'post-code-update',
              'post-db-copy',
              'post-files-copy',
            ],
            'environments' => [
              'dev',
              'test',
              'prod',
            ],
            'priority' => 0,
          ],
        ],
      ],
      'irrelevant' => [
        [
          'relevant' => FALSE,
          'not-cloud-hooks' => [
            'arbitrary data',
          ],
        ],
      ],
    ];
  }

  /**
   * Test case for the ::validate method.
   *
   * @param array $hook_config
   *   The sample data passed in from the provider.
   *
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::validate
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::validateRequiredKeys
   * @covers \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller::validateEvents
   *
   * @dataProvider hookProvider
   */
  public function testValidate(array $hook_config) {
    if (array_key_exists('error', $hook_config)) {
      static::expectException(\InvalidArgumentException::class);
      static::expectExceptionMessage($hook_config['error']);

      unset($hook_config['error']);
    }
    $this->installer->validateProxy($hook_config);

    static::assertTrue(TRUE);
  }

  /**
   * Data provider for ::testInstallHooks.
   *
   * @return array
   *   Sample data.
   */
  public function hookProvider() {
    return [
      'invalid-missing-class' => [
        [
          'events' => ['post-code-deploy'],
          'environments' => ['dev'],
          'priority' => 0,
          'error' => 'Unable to install cloudhook.  Required configuration key "class" is missing.',
        ],
      ],
      'invalid-missing-event' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'environments' => ['dev'],
          'priority' => 0,
          'error' => 'Unable to install cloudhook.  Required configuration key "events" is missing.',
        ],
      ],
      'invalid-missing-environment' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => ['post-code-deploy'],
          'priority' => '0',
          'error' => 'Unable to install cloudhook.  Required configuration key "environments" is missing.',
        ],
      ],
      'invalid-missing-priority' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => ['post-code-deploy'],
          'environments' => ['dev'],
          'error' => 'Unable to install cloudhook.  Required configuration key "priority" is missing.',
        ],
      ],
      'invalid-malformed-environments' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => ['post-code-deploy'],
          'environments' => 'dev',
          'priority' => '0',
          'error' => 'Unable to install cloudhook.  Environments are not in an acceptable format.',
        ],
      ],
      'invalid-malformed-events' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => '¯\_(ツ)_/¯',
          'environments' => ['dev'],
          'priority' => '0',
          'error' => 'Unable to install cloudhook.  Events are not in an acceptable format.',
        ],
      ],
      'invalid-unknown-event' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => ['¯\_(ツ)_/¯'],
          'environments' => ['dev'],
          'priority' => '0',
          'error' => 'Unable to install cloudhook.  Event "¯\_(ツ)_/¯" is not a recognized hook type.',
        ],
      ],
      'invalid-noninteger-priority' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => ['post-code-deploy'],
          'environments' => ['dev'],
          'priority' => '¯\_(ツ)_/¯',
          'error' => 'Unable to install cloudhook.  Priority "¯\_(ツ)_/¯" is not an integer.',
        ],
      ],
      'valid' => [
        [
          'class' => 'Drupal\\cloudhooks\\Dummy',
          'events' => ['post-code-deploy'],
          'environments' => ['dev'],
          'priority' => '0',
        ],
      ],
    ];
  }

}
