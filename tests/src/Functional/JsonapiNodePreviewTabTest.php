<?php

declare(strict_types=1);

namespace Drupal\Tests\jsonapi_node_preview_tab\Functional;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\user\UserInterface;

/**
 * Tests the JSON:API preview tab and the "JSON" node operation.
 */
#[Group('jsonapi_node_preview_tab')]
#[RunTestsInSeparateProcesses]
class JsonapiNodePreviewTabTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block', 'jsonapi_node_preview_tab', 'node', 'views'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * The node to preview.
   */
  protected NodeInterface $node;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->drupalPlaceBlock('local_tasks_block');

    $this->drupalCreateContentType(['type' => 'article']);
    // JSON:API adds the new bundle's routes when the router is rebuilt.
    $this->container->get('router.builder')->rebuild();
    $this->node = $this->drupalCreateNode([
      'type' => 'article',
      'title' => 'Preview me',
    ]);
  }

  /**
   * Tests the tab and the preview page for a user with the permission.
   */
  public function testPreviewTab(): void {
    $account = $this->drupalCreateUser([
      'access content',
      'access jsonapi preview tab',
    ]);
    $this->assertInstanceOf(UserInterface::class, $account);
    $this->drupalLogin($account);

    // The node page has the tab.
    $this->drupalGet($this->node->toUrl());
    $preview_path = $this->node->toUrl('json-preview')->toString();
    $this->assertSession()->linkByHrefExists($preview_path);

    // The preview page shows the node's JSON:API document in an iframe.
    $this->drupalGet($preview_path);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('JSON:API Preview for Preview me');
    $expected = Url::fromRoute('jsonapi.node--article.individual', [
      'entity' => $this->node->uuid(),
    ], ['absolute' => TRUE])->toString();
    $this->assertSession()->elementAttributeContains('css', 'iframe', 'src', $expected);
  }

  /**
   * Tests that a user without the permission has no tab and no access.
   */
  public function testPreviewAccessDenied(): void {
    $account = $this->drupalCreateUser(['access content']);
    $this->assertInstanceOf(UserInterface::class, $account);
    $this->drupalLogin($account);

    $preview_path = $this->node->toUrl('json-preview')->toString();

    $this->drupalGet($this->node->toUrl());
    $this->assertSession()->linkByHrefNotExists($preview_path);

    $this->drupalGet($preview_path);
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Tests the "JSON" operation on the content overview.
   */
  public function testNodeOperation(): void {
    $preview_path = $this->node->toUrl('json-preview')->toString();

    $account = $this->drupalCreateUser([
      'access content overview',
      'bypass node access',
      'access jsonapi preview tab',
    ]);
    $this->assertInstanceOf(UserInterface::class, $account);
    $this->drupalLogin($account);
    $this->drupalGet('admin/content');
    $this->assertSession()->linkByHrefExists($preview_path);

    // Without the permission, the operation is not there.
    $account = $this->drupalCreateUser([
      'access content overview',
      'bypass node access',
    ]);
    $this->assertInstanceOf(UserInterface::class, $account);
    $this->drupalLogin($account);
    $this->drupalGet('admin/content');
    $this->assertSession()->linkByHrefNotExists($preview_path);
  }

}
