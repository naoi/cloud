<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\ElasticIpInterface.
 */

// Updated by yas 2016/07/05
// Updated by yas 2016/06/03
// Updated by yas 2016/05/31
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
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
 * Provides an interface defining an ElasticIp entity.
 *
 * @ingroup aws_cloud
 */
interface ElasticIpInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function public_ip();

  /**
   * {@inheritdoc}
   */
  public function setPublicIp($public_ip = '');

  /**
   * {@inheritdoc}
   */
  public function setAllocationId($allocation_id = '');

  /**
   * {@inheritdoc}
   */
  public function instance_id();

  /**
   * {@inheritdoc}
   */
  public function domain();

  /**
   * {@inheritdoc}
   */
  public function scope();

  /**
   * {@inheritdoc}
   */
  public function network_interface_id();

  /**
   * {@inheritdoc}
   */
  public function private_ip_address();

  /**
   * {@inheritdoc}
   */
  public function network_interface_owner();

  /**
   * {@inheritdoc}
   */
  public function allocation_id();

  /**
   * {@inheritdoc}
   */
  public function association_id();

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

}
