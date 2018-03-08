<?php


// Updated by yas 2016/05/25
// updated by yas 2016/05/20
// created by yas 2016/05/19.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Volume entity.
 *
 * @see \Drupal\aws_cloud\Entity\Ec2\Volume\Entity\Volume.
 */
class VolumeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view aws cloud volume');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit aws cloud volume');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete aws cloud ebs volume');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add aws cloud volume');
  }

}
