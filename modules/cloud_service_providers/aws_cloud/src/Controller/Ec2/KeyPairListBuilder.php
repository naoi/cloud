<?php

// Updated by yas 2106/07/03
// Updated by yas 2106/06/03
// Updated by yas 2106/05/25
// udpated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Created by yas 2016/04/21.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of KeyPair.
 */
class KeyPairListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      // field should be 'field', not 'specifier' in ConfigEntity.
      ['data' => t('Key Pair Name'), 'specifier' => 'key_pair_name', 'sort' => 'ASC'],
      ['data' => t('Key Fingerprint'), 'specifier' => 'fingerprint'],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $row['key_pair_name'] = \Drupal::l(
      $entity->key_pair_name(),
      $entity->urlInfo('canonical')
             ->setRouteParameter('aws_cloud_key_pair', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );
    $row['key_fingerprint'] = $entity->key_fingerprint();

    return $row + parent::buildRow($entity);
  }

}
