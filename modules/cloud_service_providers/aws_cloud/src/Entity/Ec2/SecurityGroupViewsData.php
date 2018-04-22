<?php

/**
 * @file
 * Contains Drupal\aws_cloud\Entity\Ec2\SecurityGroupViewsData.
 */

// Updated by yas 2016/05/25
// created by yas 2016/05/20.

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudScripting entity type.
 */
class SecurityGroupViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    return $data;
  }

}
