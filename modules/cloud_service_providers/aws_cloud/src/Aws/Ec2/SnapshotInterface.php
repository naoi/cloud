<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\SnapshotInterface.
 */

// Updated by yas 2016/06/04
// Updated by yas 2016/05/28
// Updated by yas 2016/05/26
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Created by yas 2016/04/19

namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an Snapshot entity.
 *
 * @ingroup aws_cloud
 */
interface SnapshotInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function snapshot_id();

  /**
   * {@inheritdoc}
   */
  public function setSnapshotId(string $snapshot_id = '');

  /**
   * {@inheritdoc}
   */
  public function size();

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function status();

  /**
   * {@inheritdoc}
   */
  public function setStatus($status = 'unknown');

  /**
   * {@inheritdoc}
   */
  public function started();

  /**
   * {@inheritdoc}
   */
  public function setStarted($started = 0);


  /**
   * {@inheritdoc}
   */
  public function volume_id();

  /**
   * {@inheritdoc}
   */
  public function owner_id();

  /**
   * {@inheritdoc}
   */
  public function owner_aliases();

  /**
   * {@inheritdoc}
   */
  public function encrypted();

  /**
   * {@inheritdoc}
   */
  public function setEncrypted($encrypted = FALSE);

  /**
   * {@inheritdoc}
   */
  public function kms_key_id();

  /**
   * {@inheritdoc}
   */
  public function state_message();

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
