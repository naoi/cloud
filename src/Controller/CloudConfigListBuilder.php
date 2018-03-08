<?php

namespace Drupal\cloud\Controller;

// Updated by yas 2016/05/29
// Updated by yas 2015/09/28
// Created by yas 2015/06/01.
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;

/**
 * Provides a listing of Config ($cloud_context).
 */
class CloudConfigListBuilder extends ConfigEntityListBuilder {

  /**
   *
   */
  public function render() {

    $header  = $this->buildHeader();
    $storage = $this->getStorage();
    $query   = $storage->getQuery();
    $keys    = [];
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
    $rows = [];
    // Need to implement buildRow method.
    foreach ($entities as $entity) {
      $rows[] = $this->buildRow($entity);
    }

    $build['pager'] = [
      '#type' => 'pager',
    ];

    $build['tablesort_table'] = [
      '#theme'  => 'table',
      '#header' => $header,
      '#rows'   => $rows,
      '#empty'  => $this->t('There is no @label yet.', [
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

  // For reference.
  /**
   * {@inheritdoc}
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
