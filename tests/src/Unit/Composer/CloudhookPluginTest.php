<?php

namespace Drupal\Tests\cloudhooks\Unit\Composer;

use Composer\Composer;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Drupal\cloudhooks\Composer\Installer\CloudhookInstaller;
use Drupal\cloudhooks\HookRepository;
use Drupal\Tests\cloudhooks\Proxy\Composer\CloudhookPluginProxy;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudhookPluginTest.
 *
 * @covers \Drupal\cloudhooks\Composer\CloudhookPlugin
 *
 * @package \Drupal\cloudhooks\Tests\Unit
 */
class CloudhookPluginTest extends TestCase {

  /**
   * An instance of the class under test.
   *
   * @var \Drupal\Tests\cloudhooks\Proxy\Composer\CloudhookPluginProxy
   */
  protected $cloudhookPlugin;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {

    /* @var $hook_repo_mock \Drupal\cloudhooks\HookRepository|\PHPUnit\Framework\MockObject\MockObject */
    $hook_repo_mock = $this->getMockBuilder(HookRepository::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $installer_mock \Drupal\cloudhooks\Composer\Installer\CloudhookInstaller|\PHPUnit\Framework\MockObject\MockObject */
    $installer_mock = $this->getMockBuilder(CloudhookInstaller::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->cloudhookPlugin = new CloudhookPluginProxy(
      $hook_repo_mock,
      $installer_mock
    );
  }

  /**
   * Test case for the ::getSubscribedEvents method.
   *
   * @covers \Drupal\cloudhooks\Composer\CloudhookPlugin::getSubscribedEvents
   */
  public function testGetSubscribedEvents() {

    $events = CloudhookPluginProxy::getSubscribedEvents();

    static::assertArrayHasKey(ScriptEvents::POST_INSTALL_CMD, $events);
    static::assertArrayHasKey(ScriptEvents::POST_UPDATE_CMD, $events);

    $post_install_cmd = $events[ScriptEvents::POST_INSTALL_CMD];
    $post_update_cmd = $events[ScriptEvents::POST_UPDATE_CMD];

    static::assertTrue(method_exists($this->cloudhookPlugin, $post_install_cmd));
    static::assertTrue(method_exists($this->cloudhookPlugin, $post_update_cmd));
  }

  /**
   * Test case for the ::activate method.
   *
   * @covers \Drupal\cloudhooks\Composer\CloudhookPlugin::activate
   */
  public function testActivate() {

    /* @var $composer_mock \Composer\Composer|\PHPUnit\Framework\MockObject\MockObject */
    $composer_mock = $this->getMockBuilder(Composer::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $io_mock \Composer\IO\IOInterface|\PHPUnit\Framework\MockObject\MockObject */
    $io_mock = $this->getMockBuilder(IOInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    /* @var $install_mgr_mock \Composer\Installer\InstallationManager|\PHPUnit\Framework\MockObject\MockObject */
    $install_mgr_mock = $this->getMockBuilder(InstallationManager::class)
      ->disableOriginalConstructor()
      ->getMock();

    $composer_mock
      ->expects(static::once())
      ->method('getInstallationManager')
      ->willReturn($install_mgr_mock);

    $install_mgr_mock->expects(static::once())
      ->method('addInstaller');

    $this->cloudhookPlugin->activate($composer_mock, $io_mock);
  }

  /**
   * Test case for the ::installHooks method.
   *
   * @param array $hooks
   *   The sample data passed in from the provider.
   *
   * @covers \Drupal\cloudhooks\Composer\CloudhookPlugin::installHooks
   *
   * @dataProvider hookProvider
   */
  public function testInstallHooks(array $hooks) {
    $hook_repo_mock = $this->cloudhookPlugin->getHookRepository();

    $hook_repo_mock
      ->expects(static::once())
      ->method('getHooks')
      ->willReturn($hooks);

    /* @var $event \Composer\Script\Event|\PHPUnit\Framework\MockObject\MockObject */
    $event = $this->getMockBuilder(Event::class)
      ->disableOriginalConstructor()
      ->getMock();

    // @TODO: vfsStream
    $this->cloudhookPlugin->installHooks($event);
  }

  /**
   * Data provider for ::testInstallHooks.
   *
   * @return array
   *   Sample data.
   */
  public function hookProvider() {
    return [
      'default' => [
        [
          [
            'class' => 'Drupal\\cloudhooks\\Dummy',
            'event' => 'post-code-deploy',
            'environment' => 'dev',
            'priority' => '0',
          ],
          [
            'class' => 'Drupal\\cloudhooks\\Dummy',
            'event' => 'post-code-update',
            'environment' => 'dev',
            'priority' => '0',
          ],
          [
            'class' => 'Drupal\\cloudhooks\\Dummy',
            'event' => 'post-db-copy',
            'environment' => 'dev',
            'priority' => '0',
          ],
          [
            'class' => 'Drupal\\cloudhooks\\Dummy',
            'event' => 'post-files-copy',
            'environment' => 'dev',
            'priority' => '0',
          ],
        ],
      ],
    ];
  }

}
