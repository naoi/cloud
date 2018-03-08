<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\KeyPairInterface.
 */

// Updated by yas 2016/06/03
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// udpated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/11
// Created by yas 2016/04/19

namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a KeyPair entity.
 *
 * @ingroup aws_cloud
 */
interface KeyPairInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function key_pair_name();

  /**
   * {@inheritdoc}
   */
  public function key_fingerprint();

  /**
   * {@inheritdoc}
   */
  public function setKeyFingerprint($key_finterprint = '');

  /**
   * {@inheritdoc}
   */
  public function key_material();

  /**
   * {@inheritdoc}
   */
  public function setKeyMaterial($key_material = '');

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
