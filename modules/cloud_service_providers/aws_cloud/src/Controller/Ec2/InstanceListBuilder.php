<?php

// Updated by yas 2016/05/25
// udpated by yas 2016/05/21
// created by yas 2016/05/19.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
// Use Drupal\Core\Entity\EntityListBuilder; // Using CloudContentListBuilder instead of EntityListBuilder.
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a list controller for Instance entity.
 *
 * @ingroup aws_cloud
 */
class InstanceListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      ['data' => t('Name'), 'specifier' => 'name'],
      ['data' => t('Instance ID'), 'specifier' => 'instance_id'],
      ['data' => t('Public IP'), 'specifier' => 'public_ip'],
      ['data' => t('Instance State'), 'specifier' => 'instance_state'],
      ['data' => t('Zone'), 'specifier' => 'availability_zone'],
      ['data' => t('Key Pair'), 'specifier' => 'key_pair_name'],
      ['data' => t('Launched at'), 'specifier' => 'created'],
      ['data' => t('Date Updated'), 'specifier' => 'changed', 'sort' => 'DESC'],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    // For debug
    //  $row['id'] = $entity->id();
    //  $row['uuid'] = $entity->uuid();
    $row['name'] = \Drupal::l(
      $this->getLabel($entity),
      $entity->urlInfo('canonical')
             // @FIXME to use instance_id()
    //        ->setRouteParameter('aws_cloud_instance', $entity->instance_id()  )
             ->setRouteParameter('aws_cloud_instance', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );

    $row['instance_id'] = $entity->instance_id();
    $row['public_ip'] = $entity->public_ip();
    $row['instance_state'] = $entity->instance_state();
    $row['availability_zone'] = $entity->availability_zone();
    $row['key_pair_name'] = $entity->key_pair_name();
    $row['availability_zone'] = $entity->availability_zone();
    $row['created'] = date('Y/m/d H:i', $entity->created());
    $row['changed'] = date('Y/m/d H:i', $entity->changed());

    return $row + parent::buildRow($entity);
  }

}
