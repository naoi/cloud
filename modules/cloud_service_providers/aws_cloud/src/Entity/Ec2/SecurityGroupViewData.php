<?php

/**
 * @file
 * Contains Drupal\aws_cloud\Entity\Ec2\SecurityGroupViewData.
 */

// Updated by yas 2016/05/25
// created by yas 2016/05/20.
Ec2;

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

    $data['aws_cloud_security_group']['table']['base'] = [
      'field' => 'id',
      'title' => t('AWS Cloud Security Group'),
      'help'  => t('The AWC Cloud Security Group entity ID.'),
    ];

    return $data;
  }

}
