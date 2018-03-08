<?php

// Created by yas 2015/06/03.
namespace Drupal\cloud_script\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudScript entity type.
 */
class CloudScriptViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['cloud_script']['table']['base'] = [
      'field' => 'id',
      'title' => t('Cloud Scripts'),
      'help' => t('The cloud_script entity ID.'),
    ];

    return $data;
  }

}
