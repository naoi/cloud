<?php

namespace Drupal\cloud_server_template\Controller;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Cloud Server Template entity.
 *
 * @see \Drupal\cloud_server_template\Entity\CloudServerTemplate.
 */
class CloudServerTemplateAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\cloud_server_template\Entity\CloudServerTemplateInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished cloud server template entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published cloud server template entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit cloud server template entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete cloud server template entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add cloud server template entities');
  }

}
