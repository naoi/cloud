<?php


// Updated by yas 2016/05/25
// created by yas 2016/05/19.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the ElasticIp entity.
 *
 * @see \Drupal\aws_cloud\Entity\Ec2\ElasticIp\Entity\ElasticIp.
 */
class ElasticIpAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view aws cloud elastic ip');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit aws cloud elastic ip');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete aws cloud elastic ip');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add aws cloud elastic ip');
  }

}
