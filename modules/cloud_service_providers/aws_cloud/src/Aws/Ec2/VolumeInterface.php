<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\VolumeInterface.
 */

// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/11
// Created by yas 2016/04/19

namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an Volume entity.
 *
 * @ingroup aws_cloud
 */
interface VolumeInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function volume_id();

  /**
   * {@inheritdoc}
   */
  public function setVolumeId(string $volume_id = '');

  /**
   * {@inheritdoc}
   */
  public function size();

  /**
   * {@inheritdoc}
   */
  public function state();

  /**
   * {@inheritdoc}
   */
  public function setState(string $state = '');

  /**
   * {@inheritdoc}
   */
  public function volume_status();

  /**
   * {@inheritdoc}
   */
  public function attachment_information();

  /**
   * {@inheritdoc}
   */
  public function volume_type();

  /**
   * {@inheritdoc}
   */
  public function product_codes();

  /**
   * {@inheritdoc}
   */
  public function iops();

  /**
   * {@inheritdoc}
   */
  public function alarm_status();

  /**
   * {@inheritdoc}
   */
  public function snapshot_id();

  /**
   * {@inheritdoc}
   */
  public function availability_zone();

  /**
   * {@inheritdoc}
   */
  public function encrypted();

  /**
   * {@inheritdoc}
   */
  public function kms_key_id();

  /**
   * {@inheritdoc}
   */
  public function kms_key_aliases();

  /**
   * {@inheritdoc}
   */
  public function kms_key_arn();

  /**
   * {@inheritdoc}
   */
  public function created();

  /**
   * {@inheritdoc}
   */
  public function setCreated($created = 0);

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
