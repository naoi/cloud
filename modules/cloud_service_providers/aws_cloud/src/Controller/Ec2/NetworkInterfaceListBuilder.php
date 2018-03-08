<?php

// Updated by yas 2016/06/04
// Updated by yas 2016/05/25
// updated by yas 2016/05/21
// updated by yas 2016/05/19
// updated by yas 2016/05/18
// created by yas 2016/04/21.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of NetworkInterface.
 */
class NetworkInterfaceListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      // field should be 'field', not 'specifier' in ConfigEntity.
      ['data' => t('Network Interface ID'), 'specifier' => 'network_interface_id', 'sort' => 'ASC'],
      ['data' => t('Subnet ID'), 'specifier' => 'subnet_id'],
      ['data' => t('VPC ID'), 'specifier' => 'vpc_id'],
      ['data' => t('Zone'), 'specifier' => 'availability_zone'],
      ['data' => t('Security Groups'), 'specifier' => 'security_groups'],
      ['data' => t('Description'), 'specifier' => 'description'],
      ['data' => t('Instance ID'), 'specifier' => 'instance_id'],
      ['data' => t('Status'), 'specifier' => 'status'],
      ['data' => t('Public IP'), 'specifier' => 'public_ips'],
      ['data' => t('Primary Private IP'), 'specifier' => 'primary_private_ip'],
      ['data' => t('Secondary Private IP'), 'specifier' => 'secondary_private_ips'],
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
             // @FIXME to use network_interface_id()
    //           ->setRouteParameter('aws_cloud_network_interface', $entity->network_interface_id())
             ->setRouteParameter('aws_cloud_network_interface', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );
    $row['subnet_id'] = $entity->subnet_id();
    $row['vpc_id'] = $entity->vpc_id();
    $row['availability_zone'] = $entity->availability_zone();
    $row['security_groups'] = $entity->security_groups();
    $row['description'] = $entity->description();
    $row['instance_id'] = $entity->instance_id();
    $row['status'] = $entity->status();
    $row['public_ips'] = $entity->public_ips();
    $row['primary_private_ip'] = $entity->primary_private_ip();
    $row['secondary_private_ips'] = $entity->secondary_private_ips();

    return $row + parent::buildRow($entity);
  }

}
