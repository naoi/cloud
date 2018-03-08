<?php

// Updated by yas 2016/06/04
// Updated by yas 2016/05/21
// updated by yas 2016/05/19
// updated by yas 2016/05/18
// created by yas 2016/04/21.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Snapshot.
 */
class SnapshotListBuilder extends CloudContentListBuilder {

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
      ['data' => t('Snapshot ID'), 'specifier' => 'snapshot_id'],
      ['data' => t('Size'), 'specifier' => 'size'],
      ['data' => t('Description'), 'specifier' => 'description'],
      ['data' => t('Status'), 'specifier' => 'status'],
      ['data' => t('Started'), 'specifier' => 'started'],
      ['data' => t('Progress'), 'specifier' => 'progress'],
      ['data' => t('Encrypted'), 'specifier' => 'encrypted'],
      ['data' => t('KMS Key ID'), 'specifier' => 'kms_key_id'],
      ['data' => t('KMS Key Alias'), 'specifier' => 'kms_key_aliases'],
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
             // @FIXME to use snapshot_id()
    //           ->setRouteParameter('aws_cloud_snapshot', $entity->snapshot_id()  )
             ->setRouteParameter('aws_cloud_snapshot', $entity->id())
             ->setRouteParameter('cloud_context', $entity->cloud_context())
    );
    $row['snapshot_id'] = $entity->snapshot_id();
    $row['size'] = $entity->size();
    $row['description'] = $entity->description();
    $row['status'] = $entity->status();
    $row['started'] = date('Y/m/d H:i', $entity->started());
    $row['progress'] = $entity->progress();
    $row['encrypted'] = $entity->encrypted();
    $row['kms_key_id'] = $entity->kms_key_id();
    $row['kms_key_aliases'] = $entity->kms_key_aliases();

    return $row + parent::buildRow($entity);
  }

}
