<?php

// Updated by yas 2015/06/03
// Updated by yas 2015/06/01
// Updated by yas 2015/05/31
// Created by yas 2015/05/30.

namespace Drupal\cloud_server_template\Controller;

use Drupal\cloud\Controller\CloudContentListBuilder;
// Use Drupal\Core\Entity\EntityListBuilder; // Using CloudEntityListBuilder instead of EntityListBuilder.
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a list controller for CloudServerTemplate entity.
 *
 * @ingroup cloud_server_template
 */
class CloudServerTemplateListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      ['data' => t('Name'), 'specifier' => 'name'],
      ['data' => t('Description'), 'specifier' => 'description'],
      ['data' => t('Instance Type'), 'specifier' => 'instance_type'],
      ['data' => t('Count'), 'specifier' => 'instance_count'],
      ['data' => t('Date Created'), 'specifier' => 'created'],
      ['data' => t('Date Updated'), 'specifier' => 'changed', 'sort' => 'DESC'],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    // For debug
    //  $row['id'] = $entity->id();
    //  $row['uuid'] = $entity->uuid();
    $row['name'] = \Drupal::l(
      $this->getLabel($entity),
      new Url(
        'entity.cloud_server_template.canonical', [
          'cloud_server_template' => $entity->id(),
    // Need to add.
          'cloud_context' => $entity->cloud_context(),
        ]
      )
    );
    $row['description'] = $entity->description();
    $row['instance_type'] = $entity->instance_type();
    $row['instance_count'] = $entity->instance_count();
    $row['created'] = date('Y/m/d H:i', $entity->created());
    $row['changed'] = date('Y/m/d H:i', $entity->changed());

    return $row + parent::buildRow($entity);
  }

}
