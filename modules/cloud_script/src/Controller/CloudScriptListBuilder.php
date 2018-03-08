<?php

// Created by yas 2015/06/03.
namespace Drupal\cloud_script\Controller;

use Drupal\cloud\Controller\CloudContentListBuilder;
// Use Drupal\Core\Entity\EntityListBuilder; // Using CloudContentListBuilder instead of EntityListBuilder.
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a list controller for CloudScript entity.
 *
 * @ingroup cloud_script
 */
class CloudScriptListBuilder extends CloudContentListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {

    $header = [
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      ['data' => t('Name'), 'specifier' => 'name'],
      ['data' => t('Description'), 'specifier' => 'description'],
      ['data' => t('Type'), 'specifier' => 'type'],
      ['data' => t('Input Parameters'), 'specifier' => 'input_parameters'],
      ['data' => t('Date Created'), 'specifier' => 'created'],
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
      new Url(
        'entity.cloud_script.canonical', [
          'cloud_script' => $entity->id(),
    // 'cloud_context' => $entity->cloud_context(), // Need to add.
        ]
      )
    );
    $row['description'] = $entity->description();
    $row['type'] = $entity->type();
    $row['input_paramters'] = $entity->input_parameters();
    $row['created'] = date('Y/m/d H:i', $entity->created());
    $row['changed'] = date('Y/m/d H:i', $entity->changed());

    return $row + parent::buildRow($entity);
  }

}
