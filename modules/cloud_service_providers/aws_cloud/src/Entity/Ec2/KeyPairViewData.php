<?php


// Updated by yas 2016/05/25
// created by yas 2016/05/20.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the CloudScripting entity type.
 */
class KeyPairViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['aws_cloud_key_pair']['table']['base'] = [
      'field' => 'id',
      'title' => t('AWS Cloud KeyPair'),
      'help'  => t('The AWC Cloud KeyPair entity ID.'),
    ];

    return $data;
  }

}
