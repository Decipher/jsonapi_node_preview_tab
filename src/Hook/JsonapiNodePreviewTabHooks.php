<?php

declare(strict_types=1);

namespace Drupal\jsonapi_node_preview_tab\Hook;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Hook implementations for jsonapi_node_preview_tab.
 */
class JsonapiNodePreviewTabHooks {

  use StringTranslationTrait;

  public function __construct(
    protected AccountInterface $currentUser,
  ) {}

  /**
   * Implements hook_entity_type_build().
   */
  #[Hook('entity_type_build')]
  public function entityTypeBuild(array &$entity_types): void {
    // @todo make this work for other entity types?
    $entity_type_id = 'node';
    $entity_types[$entity_type_id]->setLinkTemplate('json-preview', "/{$entity_type_id}/{{$entity_type_id}}/json-preview");
  }

  /**
   * Implements hook_entity_operation().
   *
   * Drupal 11.3 and later pass $cacheability. Earlier versions do not.
   */
  #[Hook('entity_operation')]
  public function entityOperation(EntityInterface $entity, ?CacheableMetadata $cacheability = NULL): array {
    if (!$entity->hasLinkTemplate('json-preview')) {
      return [];
    }
    // The operation depends on the user's permissions.
    $cacheability?->addCacheContexts(['user.permissions']);
    if (!$this->currentUser->hasPermission('access jsonapi preview tab')) {
      return [];
    }
    return [
      'json' => [
        'title' => $this->t('JSON'),
        'weight' => 50,
        'url' => $entity->toUrl('json-preview'),
      ],
    ];
  }

}
