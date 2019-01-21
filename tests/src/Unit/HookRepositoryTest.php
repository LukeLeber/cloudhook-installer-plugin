<?php

namespace Drupal\Tests\cloudhooks\Unit;

use Drupal\Tests\cloudhooks\Proxy\HookRepositoryProxy;
use PHPUnit\Framework\TestCase;

/**
 * Class HookRepositoryTest.
 *
 * @package Drupal\Tests\cloudhooks
 */
class HookRepositoryTest extends TestCase {

  /**
   * An instance of the class under test.
   *
   * @var \Drupal\Tests\cloudhooks\Proxy\HookRepositoryProxy
   */
  protected $hookRepository;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->hookRepository = new HookRepositoryProxy();
  }

  /**
   * Test case for the ::getHooks method.
   *
   * @covers \Drupal\cloudhooks\HookRepository::getHooks
   */
  public function testGetHooks() {
    $this->hookRepository->hooks = ['test data'];

    static::assertSame($this->hookRepository->hooks, $this->hookRepository->getHooks());
  }

  /**
   * Test case for the ::register method.
   *
   * @param array $hook
   *   The sample data to test.
   *
   * @covers \Drupal\cloudhooks\HookRepository::register
   *
   * @dataProvider hookProvider
   */
  public function testRegister(array $hook) {
    $this->hookRepository->register($hook['class'], $hook['event'], $hook['environment'], $hook['priority']);

    static::assertSame([$hook], $this->hookRepository->hooks);
  }

  /**
   * Data provider for ::testRegister.
   *
   * @return array
   *   Sample data.
   */
  public function hookProvider() {
    return [
      'default' => [
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
    ];
  }

}
