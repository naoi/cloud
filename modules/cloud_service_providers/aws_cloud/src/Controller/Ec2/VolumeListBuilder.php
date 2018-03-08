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
 * Provides a listing of Volume.
 */
class VolumeListBuilder extends CloudContentListBuilder {

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
      ['data' => t('Volume ID'), 'specifier' => 'volume_id'],
      ['data' => t('Size'), 'specifier' => 'size'],
      ['data' => t('Volume Type'), 'specifier' => 'volume_type'],
      ['data' => t('IOPS'), 'specifier' => 'iops'],
      ['data' => t('Snapshot'), 'specifier' => 'snapshot_id'],
      ['data' => t('Created'), 'specifier' => 'created'],
      ['data' => t('Zone'), 'specifier' => 'availability_zone'],
      ['data' => t('State'), 'specifier' => 'state'],
      ['data' => t('Alarm Status'), 'specifier' => 'alarm_status'],
      ['data' => t('Attachment Information'), 'specifier' => 'attachment_information'],
      ['data' => t('Volume Status'), 'specifier' => 'volume_status'],
      ['data' => t('Encrypted'), 'specifier' => 'encrypted'],
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
             // @FIXME to use volume_id()
    //           ->setRouteParameter('aws_cloud_volume', $entity->volume_id()  )
             ->setRouteParameter('aws_cloud_volume', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );
    $row['volume_id'] = $entity->volume_id();
    $row['size'] = $entity->size();
    $row['volume_type'] = $entity->volume_type();
    $row['iops'] = $entity->iops();
    $row['snapshot_id'] = $entity->snapshot_id();
    $row['created'] = date('Y/m/d H:i', $entity->created());
    $row['availability_zone'] = $entity->availability_zone();
    $row['state'] = $entity->state();
    $row['alarm_status'] = $entity->alarm_status();
    $row['attachment_information'] = $entity->attachment_information();
    $row['volume_status'] = $entity->volume_status();
    $row['encrypted'] = $entity->encrypted();

    return $row + parent::buildRow($entity);
  }

}
