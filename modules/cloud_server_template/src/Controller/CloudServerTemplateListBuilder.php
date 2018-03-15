<?php

// Updated by yas 2015/06/03
// Updated by yas 2015/06/01
// Updated by yas 2015/05/31
// Created by yas 2015/05/30.

namespace Drupal\cloud_server_template\Controller;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;


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
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.cloud_server_template.canonical',
      ['cloud_server_template' => $entity->id()]
    );

    return $row + parent::buildRow($entity);
  }

}
