<?php

// Udpated by yas 2016/07/05
// Udpated by yas 2016/06/03
// Udpated by yas 2016/05/25
// Updated by yas 2016/05/23
// Updated by yas 2016/05/21
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Created by yas 2016/04/21.

namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of ElasticIp.
 */
class ElasticIpListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      // field should be 'field', not 'specifier' in ConfigEntity.
      ['data' => t('Name'), 'specifier' => 'name', 'sort' => 'ASC'],
      ['data' => t('Elastic IP'), 'specifier' => 'public_ip'],
      ['data' => t('Allocation ID'), 'specifier' => 'allocation_id'],
      ['data' => t('Instance'), 'specifier' => 'instance_id'],
      ['data' => t('Private IP Address'), 'specifier' => 'private_ip_address'],
      ['data' => t('Scope'), 'specifier' => 'scope'],
//    array('data' => t('Public DNS'), 'specifier' => 'public_dns'), // Need to get from Network Interface
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $row['name'] = \Drupal::l(
      $this->getLabel($entity),
      $entity->urlInfo('canonical')
           // @FIXME to use image_id()
    //         ->setRouteParameter('aws_cloud_elastic_ip', $entity->elastic_ip()  )
           ->setRouteParameter('aws_cloud_elastic_ip', $entity->id())
           ->setRouteParameter('cloud_context', $entity->cloud_context())
    );
    $row['public_ip'] = $entity->public_ip();
    $row['allocation_id'] = $entity->allocation_id();
    $row['instance_id'] = $entity->instance_id();
    $row['private_ip_address'] = $entity->private_ip_address();
    $row['scope'] = $entity->scope();
//  $row['public_dns'] = $entity->public_dns(); // Need to get from Network Interface

    return $row + parent::buildRow($entity);
  }

}
