<?php

// Created by yas 2016/05/25.
namespace Drupal\cloud_cluster\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudCluster entity type.
 */
class CloudClusterViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['cloud_cluster']['table']['base'] = [
      'field' => 'id',
      'title' => t('Cloud Cluster'),
      'help'  => t('The cloud_cluster entity ID.'),
    ];

    return $data;
  }

}
