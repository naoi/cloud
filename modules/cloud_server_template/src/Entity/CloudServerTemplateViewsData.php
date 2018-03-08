<?php

// Created by yas 2015/05/30.
namespace Drupal\cloud_server_template\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudServerTemplate entity type.
 */
class CloudServerTemplateViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['cloud_server_template']['table']['base'] = [
      'field' => 'id',
      'title' => t('Cloud Server Template'),
      'help'  => t('The cloud_server_template entity ID.'),
    ];

    return $data;
  }

}
