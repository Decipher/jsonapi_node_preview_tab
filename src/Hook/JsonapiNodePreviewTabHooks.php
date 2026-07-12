<?php

namespace Drupal\jsonapi_node_preview_tab\Hook;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Hook\Attribute\Hook;
/**
 * Hook implementations for jsonapi_node_preview_tab.
 */
class JsonapiNodePreviewTabHooks
{
    /**
     * Implements hook_entity_type_build().
     */
    #[Hook('entity_type_build')]
    public function entityTypeBuild(array &$entity_types)
    {
        // @todo make this work for other entity types?
        $entity_type_id = 'node';
        $entity_types[$entity_type_id]->setLinkTemplate('json-preview', "/{$entity_type_id}/{{$entity_type_id}}/json-preview");
    }
}
