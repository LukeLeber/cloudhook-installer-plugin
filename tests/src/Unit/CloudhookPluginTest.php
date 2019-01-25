<?php

namespace lleber\Tests\Unit;

use Composer\Script\ScriptEvents;
use lleber\Tests\CloudhookPluginProxy;
use PHPUnit\Framework\TestCase;

class CloudhookPluginTest extends TestCase {

  /**
   * The cloudhook plugin under test.
   *
   * @var \lleber\Composer\CloudhookPlugin
   */
  protected $cloudhookPlugin;

  protected function setUp() {
    $this->cloudhookPlugin = new CloudhookPluginProxy();
  }

  /**
   * {@inheritdoc}
   */
  public function testGetSubscribedEvents() {

    $events = $this->cloudhookPlugin->getSubscribedEvents();

    $this->assertArrayHasKey(ScriptEvents::POST_INSTALL_CMD, $events);
    $this->assertArrayHasKey(ScriptEvents::POST_UPDATE_CMD, $events);

    $post_install_cmd = $events[ScriptEvents::POST_INSTALL_CMD];
    $post_update_cmd = $events[ScriptEvents::POST_UPDATE_CMD];

    $this->assertTrue(method_exists($this->cloudhookPlugin, $post_install_cmd));
    $this->assertTrue(method_exists($this->cloudhookPlugin, $post_update_cmd));
  }
}