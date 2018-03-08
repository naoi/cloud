<?php

/**
 * @file
 * Contains Drupal\cloud_server_template\CloudServerTemplateInterface.
 */

// created by yas 2015/05/30

namespace Drupal\cloud_server_template;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a CloudServerTemplate entity.
 *
 * @ingroup cloud_server_template
 */
interface CloudServerTemplateInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function instance_type();

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function image_id();

  /**
   * {@inheritdoc}
   */
  public function kernel_id();

  /**
   * {@inheritdoc}
   */
  public function ramdisk_id();

  /**
   * {@inheritdoc}
   */
  public function group_id();

  /**
   * {@inheritdoc}
   */
  public function ssh_key_id();

  /**
   * {@inheritdoc}
   */
  public function user_data();

  /**
   * {@inheritdoc}
   */
  public function instance_count();

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
  public function setCloudContext($cloud_context = 'default_cloud_context');

  /**
   * The comment language code.
   */
  public function langcode();

}
