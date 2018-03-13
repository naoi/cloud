<?php

// Updated by yas 2015/09/28
// Updated by yas 2015/06/08
// Created by yas 2015/06/01.
namespace Drupal\cloud\Controller;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of CloudEntity.
 */
class CloudContentListBuilder extends EntityListBuilder {

  /**
   * Method takes cloud_context into the querying.
   */
  public function render() {

    $header = $this->buildHeader();
    $storage = $this->getStorage();
    $query = $storage->getQuery();

    // Get cloud_context from a path.
    $cloud_context = \Drupal::routeMatch()->getParameter('cloud_context');

    if (isset($cloud_context)) {
      $keys = $query->tableSort($header)
        ->condition('cloud_context', $cloud_context)
        ->execute();
    }
    else {
      $keys = $query->tableSort($header)
        ->execute();
    }

    $entities = $storage->loadMultiple($keys);
    // You need to implement buildRow method.
    $rows = [];
    foreach ($entities as $entity) {
      $rows[] = $this->buildRow($entity);
    }

    $build['pager'] = [
      '#type' => 'pager',
    ];

    $build['tablesort_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('There is no @label yet.', [
        '@label' => $this->entityType->getLabel(),
      ]),
    ];

    // Tips by yas 2015/09/28: don't return $build + parent::render()
    // It produces two lists ("$build" + "parent::render") in one page.
    //  return $build + parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);
    foreach ($operations as $key => $operation) {
      if (method_exists($entity, 'cloud_context')) {
        $operations[$key]['url']
          ->setRouteParameter('cloud_context', $entity->cloud_context());
      }
    }
    return $operations;
  }

  /**
   * {@inheritdoc}
   * For reference.
   */
  /*
  public function getDefaultOperations(EntityInterface $entity) {
  $operations = parent::getDefaultOperations($entity);
  if ($entity->hasLinkTemplate('edit-form')) {
  $operations['edit'] = array(
  'title' => t('Edit Cloud Pricing'),
  'weight' => 20,
  'url' => $entity->urlInfo('edit-form'),
  );
  }
  return $operations;
  }
   */
}
