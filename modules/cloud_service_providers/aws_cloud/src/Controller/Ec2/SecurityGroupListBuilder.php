<?php

// Updated by yas 2016/05/25
// updated by yas 2016/05/21
// updated by yas 2016/05/19
// updated by yas 2016/05/18
// created by yas 2016/04/21.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of SecurityGroup.
 */
class SecurityGroupListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      // field should be 'field', not 'specifier' in ConfigEntity.
      ['data' => t('Group Name'), 'specifier' => 'group_name'],
      ['data' => t('Group ID'), 'specifier' => 'group_id'],
      ['data' => t('VPC ID'), 'specifier' => 'vpc_id'],
      ['data' => t('Description'), 'specifier' => 'description'],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $row['group_name'] = \Drupal::l(
      $entity->group_name(),
      $entity->urlInfo('canonical')
             // @FIXME to use instance_id()
    //           ->setRouteParameter('aws_cloud_security_group', $entity->group_id())
             ->setRouteParameter('aws_cloud_security_group', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );
    $row['group_id'] = $entity->group_id();
    $row['vpc_id'] = $entity->vpc_id();
    $row['description'] = $entity->description();

    return $row + parent::buildRow($entity);
  }

}
