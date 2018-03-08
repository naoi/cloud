<?php

// Created by yas 2016/05/25.
namespace Drupal\cloud_cluster\Controller;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a list controller for CloudCluster entity.
 *
 * @ingroup cloud_cluster
 */
class CloudClusterListController extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      ['data' => t('Name'), 'specifier' => 'name', 'sort' => 'ASC'],
      ['data' => t('Description'), 'specifier' => 'description'],
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
        'entity.cloud_cluster.canonical', [
          'cloud_cluster' => $entity->id(),
        ]
      )
    );
    $row['description'] = $entity->description();

    return $row + parent::buildRow($entity);

  }

}
