<?php


// Updated by yas 2016/05/25
// udpated by yas 2016/05/20
// created by yas 2016/05/19.
namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Image entity.
 *
 * @see \Drupal\aws_cloud\Entity\Ec2\Image\Entity\Image.
 */
class ImageAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view aws cloud image');

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit aws cloud image');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete aws cloud image');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add aws cloud image');
  }

}
