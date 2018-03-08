<?php

namespace Drupal\cloud_alert\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudAlert entity type.
 */
class CloudAlertViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['cloud_alert']['table']['base'] = [
      'field' => 'id',
      'title' => t('Cloud Alert'),
      'help'  => t('The cloud_alert entity ID.'),
    ];

    return $data;
  }

}
