<?php

// Updated by yas 2016/05/25
// updated by yas 2015/06/08
// created by yas 2015/05/30.
namespace Drupal\cloud_server_template\Controller;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the CloudServerTemplate entity.
 *
 * @see \Drupal\cloud_server_template\Entity\CloudServerTemplate.
 */
class CloudServerTemplateAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view cloud server template');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit cloud server template');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete cloud server template');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add cloud server template');
  }

}
