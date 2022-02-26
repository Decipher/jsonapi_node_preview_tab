<?php

namespace Drupal\jsonapi_node_preview_tab\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Returns responses for JSON:API Node Preview Tab routes.
 */
class JsonapiNodePreviewTabController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build(Node $node) {
    $path = Url::fromRoute(
      "jsonapi.node--{$node->bundle()}.individual",
      ['entity' => $node->uuid()],
      ['absolute' => TRUE]
    )->toString();

    $build['preview'] = [
      '#type' => 'inline_template',
      '#template' => '<iframe src="{{ url }}" allow="fullscren" style="width:100%;min-height:600px;"></iframe>',
      '#context' => [
        'url' => $path,
      ],
    ];

    return $build;
  }

  /**
   * Builds the response.
   */
  public function getTitle(Node $node) {

    $title = $this->t('JSON:API Preview for @title', ['@title' => $node->label()]);

    return $title;
  }

}
