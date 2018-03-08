<?php

// Updated by yas 2016/05/23.
namespace Drupal\cloud_alert\Controller;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the cloud alert.
 *
 * @see \Drupal\cloud_alert\Entity\CloudAlert.
 */
class CloudAlertAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view cloud alert');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit cloud alert');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete cloud alert');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add cloud alert');
  }

}
