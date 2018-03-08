<?php

// Created by yas 2016/05/25.
namespace Drupal\cloud_cluster\Controller;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the cloud cluster.
 *
 * @see \Drupal\cloud_cluster\Entity\CloudCluster.
 */
class CloudClusterAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view cloud cluster');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit cloud cluster');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete cloud cluster');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add cloud cluster');
  }

}
