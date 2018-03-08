<?php

// Updated by yas 2016/05/24.
namespace Drupal\cloud_alert\Controller;

use Drupal\cloud\Controller\CloudContentListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a list controller for CloudAlert entity.
 *
 * @ingroup cloud_alert
 */
class CloudAlertListController extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      ['data' => t('Name'), 'specifier' => 'name', 'sort' => 'ASC'],
      ['data' => t('Description'), 'specifier' => 'description'],
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
      new Url(
        'entity.cloud_alert.canonical', [
          'cloud_alert' => $entity->id(),
        ]
      )
    );
    $row['description'] = $entity->description();

    return $row + parent::buildRow($entity);

  }

}
