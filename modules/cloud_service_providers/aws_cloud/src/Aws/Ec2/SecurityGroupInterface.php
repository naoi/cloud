<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\SecurityGroupInterface.
 */

// Updated by yas 2016/06/03
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Created by yas 2016/04/19

namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a SecurityGroup entity.
 *
 * @ingroup aws_cloud
 */
interface SecurityGroupInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function group_id();

  /**
   * {@inheritdoc}
   */
  public function setGroupId($group_id = '');

  /**
   * {@inheritdoc}
   */
  public function group_name();

  /**
   * {@inheritdoc}
   */
  public function group_description();

  /**
   * {@inheritdoc}
   */
  public function vpc_id();

  /**
   * {@inheritdoc}
   */
  public function created();

  /**
   * {@inheritdoc}
   */
  public function changed();

  /**
   * {@inheritdoc}
   */
  public function refreshed();

  /**
   * {@inheritdoc}
   */
  public function setRefreshed($time);

  /**
   * {@inheritdoc}
   */
/*
  public function setCloudContext($cloud_context);
*/
}
