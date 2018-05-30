<?php

namespace Drupal\cloud\Controller;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Cloud config entity.
 *
 * @see \Drupal\cloud\Entity\CloudConfig.
 */
class CloudConfigAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\cloud\Entity\CloudConfigInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished cloud config entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published cloud config entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit cloud config entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete cloud config entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add cloud config entities');
  }

}
