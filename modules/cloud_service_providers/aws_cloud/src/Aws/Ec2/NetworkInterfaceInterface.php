<?php

// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Created by yas 2016/04/19.
namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a NetworkInterface entity.
 *
 * @ingroup aws_cloud
 */
interface NetworkInterfaceInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * {@inheritdoc}
   */
  /*
  public function cloud_context();
   */

  /**
   * {@inheritdoc}
   */
  public function network_interface_id();

  /**
   * {@inheritdoc}
   */
  public function setNetworkInterfaceId(string $network_interface);

  /**
   * {@inheritdoc}
   */
  public function vpc_id();

  /**
   * {@inheritdoc}
   */
  public function setVpcId(string $vpc_id = '');

  /**
   * {@inheritdoc}
   */
  public function mac_address();

  /**
   * {@inheritdoc}
   */
  public function security_groups();

  /**
   * {@inheritdoc}
   */
  public function status();

  /**
   * {@inheritdoc}
   */
  public function setStatus(string $status = '');

  /**
   * {@inheritdoc}
   */
  public function private_dns();

  /**
   * {@inheritdoc}
   */
  public function primary_private_ip();

  /**
   * {@inheritdoc}
   */
  public function primary();

  /**
   * {@inheritdoc}
   */
  public function secondary_private_ips();

  /**
   * {@inheritdoc}
   */
  public function attachment_id();

  /**
   * {@inheritdoc}
   */
  public function attachment_owner();

  /**
   * {@inheritdoc}
   */
  public function attachment_status();

  /**
   * {@inheritdoc}
   */
  public function owner_id();

  /**
   * {@inheritdoc}
   */
  public function association_id();

  /**
   * {@inheritdoc}
   */
  public function subnet_id();

  /**
   * {@inheritdoc}
   */
  public function availability_zone();

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function public_ips();

  /**
   * {@inheritdoc}
   */
  public function source_dest_check();

  /**
   * {@inheritdoc}
   */
  public function instance_id();

  /**
   * {@inheritdoc}
   */
  public function device_index();

  /**
   * {@inheritdoc}
   */
  public function delete_on_termination();

  /**
   * {@inheritdoc}
   */
  public function allocation_id();

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
