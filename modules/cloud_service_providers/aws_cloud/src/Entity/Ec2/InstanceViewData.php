<?php


// Updated by yas 2016/05/26
// updated by yas 2016/05/25
// created by yas 2016/05/20.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudScripting entity type.
 */
class InstanceViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['aws_cloud_instance']['table']['base'] = [
      'field' => 'id',
      'title' => t('AWS Cloud Instance'),
      'help'  => t('The AWC Cloud Instance entity ID.'),
    ];

    $data['aws_cloud_instance']['instance_id'] = [
      'title' => t('Instance ID'),
      'help' => t('AWS Cloud Instance ID'),
      'field' => [
        'id' => 'instance_id',
      ],
    ];
    return $data;
  }

}
