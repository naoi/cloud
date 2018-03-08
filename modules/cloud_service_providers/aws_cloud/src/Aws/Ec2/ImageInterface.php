<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\ImageInterface.
 */

// Updated by yas 2016/06/12
// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/18
// Created by yas 2016/04/19

namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an Image entity.
 *
 * @ingroup aws_cloud
 */
interface ImageInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function image_id();

  /**
   * {@inheritdoc}
   */
  public function setImageId($image_id = '');

  /**
   * {@inheritdoc}
   */
  public function instance_id();

  /**
   * {@inheritdoc}
   */
  public function status();

  /**
   * {@inheritdoc}
   */
  public function architecture();

  /**
   * {@inheritdoc}
   */
  public function virtualization_type();

  /**
   * {@inheritdoc}
   */
  public function root_device_name();

  /**
   * {@inheritdoc}
   */
  public function ramdisk_id();

  /**
   * {@inheritdoc}
   */
  public function product_code();

  /**
   * {@inheritdoc}
   */
  public function ami_name();

  /**
   * {@inheritdoc}
   */
  public function source();

  /**
   * {@inheritdoc}
   */
  public function state_reason();

  /**
   * {@inheritdoc}
   */
  public function platform();

  /**
   * {@inheritdoc}
   */
  public function image_type();

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function root_device_type();

  /**
   * {@inheritdoc}
   */
  public function kernel_id();

  /**
   * {@inheritdoc}
   */
  public function block_devices();

  /**
   * {@inheritdoc}
   */
  public function owner();

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
