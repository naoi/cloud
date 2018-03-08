<?php

// Updated by yas 2016/06/01
// Updated by yas 2016/05/25
// updated by yas 2016/05/19
// updated by yas 2015/06/14
// created by yas 2015/06/05.
namespace Drupal\aws_cloud\Controller\Config;

use Drupal\cloud\Controller\CloudConfigListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a listing of AwsCloud.
 */
class ConfigListBuilder extends CloudConfigListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      // field should be 'field', not 'specifier' in ConfigEntity.
      ['data' => t('Cloud Display Name'), 'specifier' => 'label', 'sort' => 'ASC'],
      ['data' => t('Cloud ID'), 'specifier' => 'id'],
      ['data' => t('Region'), 'specifier' => 'region'],
      ['data' => t('API Endpoint URI'), 'specifier' => 'endpoint'],
      ['data' => t('User ID'), 'specifier' => 'user_id'],
      ['data' => t('Date Created'), 'specifier' => 'created'],
      ['data' => t('Date Updated'), 'specifier' => 'changed'],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $row['label'] = \Drupal::l(
      $entity->label(),
      new Url(
        'entity.cloud_context.edit_form', [
          // Note: id = "aws_cloud" needs to match to *.routing.yml's parameter: {aws_cloud}
    //        'cloud_context' => $entity->id(),      // equals to $entity->context()
    // equals to $entity->id()
          'cloud_context' => $entity->cloud_context(),
        ]
      )
    );
    $row['id'] = $entity->cloud_context();
    $row['region'] = $entity->region();
    $row['endpoint'] = $entity->endpoint();
    $row['user_id'] = $entity->user_id();
    $row['created'] = date('Y/m/d H:i', $entity->created());
    $row['changed'] = date('Y/m/d H:i', $entity->changed());

    return $row + parent::buildRow($entity);
  }

}
