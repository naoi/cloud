<?php

// Updated by yas 2015/06/04
// updated by yas 2015/06/01.
namespace Drupal\cloud_pricing\Controller;

use Drupal\cloud\Controller\CloudConfigListBuilder;
// Use Drupal\Core\Config\Entity\ConfigEntityListBuilder;  // Using CloudConfigListBuilder instead of ConfigEntityListBuilder.
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a listing of CloudPricing.
 */
class CloudPricingListBuilder extends CloudConfigListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      ['data' => t('Instance Type'), 'specifier' => 'instance_type'],
      ['data' => t('Description'), 'specifier' => 'description'],
      ['data' => t('Linux Usage'), 'specifier' => 'linux_usage', 'sort' => 'ASC'],
      ['data' => t('Windows Usage'), 'specifier' => 'windows_usage'],
      ['data' => t('Date Created'), 'specifier' => 'created'],
      ['data' => t('Date Updated'), 'specifier' => 'changed'],
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
    $row['instance_type'] = \Drupal::l(
      $entity->instance_type(),
      new Url(
          'entity.cloud_pricing.edit_form', [
            'cloud_pricing' => $entity->id(),
    // Need to add.
            'cloud_context' => $entity->cloud_context(),
          ]
      )
    );
    $row['description'] = $entity->description();
    $row['linux_usage'] = '$' . number_format($entity->linux_usage(), 3);
    $row['windows_usage'] = '$' . number_format($entity->windows_usage(), 3);
    $row['created'] = date('Y/m/d H:i', $entity->created());
    $row['changed'] = date('Y/m/d H:i', $entity->changed());

    return $row + parent::buildRow($entity);
  }

}
