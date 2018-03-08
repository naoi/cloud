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
 * Access controller for the Snapshot entity.
 *
 * @see \Drupal\aws_cloud\Entity\Ec2\Snapshot\Entity\Snapshot.
 */
class SnapshotAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view aws cloud snapshot');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit aws cloud snapshot');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete aws cloud snapshot');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add aws cloud snapshot');
  }

}
