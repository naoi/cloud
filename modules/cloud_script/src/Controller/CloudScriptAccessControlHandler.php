<?php


// Updated by yas 2016/05/25
// updated by yas 2016/05/24
// updated by yas 2015/06/08
// created by yas 2015/05/30.
namespace Drupal\cloud_script\Controller;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the CloudScript entity.
 *
 * @see \Drupal\cloud_script\Entity\CloudScript.
 */
class CloudScriptAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view cloud script');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit cloud script');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete cloud script');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add cloud script');
  }

}
