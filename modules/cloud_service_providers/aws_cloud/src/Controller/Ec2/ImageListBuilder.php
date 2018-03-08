<?php

// Updated by yas 2016/05/29
// updated by yas 2016/05/25
// updated by yas 2016/05/21
// updated by yas 2016/05/19
// updated by yas 2016/05/18
// created by yas 2016/04/21.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Image.
 */
class ImageListBuilder extends CloudContentListBuilder {

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
      ['data' => t('AMI Name'), 'specifier' => 'ami_name'],
      ['data' => t('AMI ID'), 'specifier' => 'image_id'],
      ['data' => t('Source'), 'specifier' => 'source'],
      ['data' => t('Owner'), 'specifier' => 'owner'],
      ['data' => t('Visibility'), 'specifier' => 'visibility'],
      ['data' => t('Status'), 'specifier' => 'status'],
      ['data' => t('Architecture'), 'specifier' => 'architecture '],
      ['data' => t('Platform'), 'specifier' => 'platform'],
      ['data' => t('Root Device    '), 'specifier' => 'root_device type'],
      ['data' => t('Virtualization'), 'specifier' => 'virtualization_type'],
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
             // @FIXME to use instance_id()
    //           ->setRouteParameter('aws_cloud_instance', $entity->instance_id()  )
             ->setRouteParameter('aws_cloud_instance', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );

    $row['ami_name'] = $entity->ami_name();
    $row['image_id'] = $entity->image_id();
    $row['source'] = $entity->source();
    $row['owner'] = $entity->owner();
    $row['visibility'] = $entity->visibility();
    $row['status'] = $entity->status();
    $row['architecture'] = $entity->architecture();
    $row['platform'] = $entity->platform();
    $row['root_device_type'] = $entity->root_device_type();
    $row['virtualization_type'] = $entity->virtualization_type();

    return $row + parent::buildRow($entity);
  }

}
