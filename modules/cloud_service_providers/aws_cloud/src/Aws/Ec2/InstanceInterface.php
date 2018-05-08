<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Aws\Ec2\InstanceInterface.
 */

// Updated by yas 2016/07/06
// Updated by yas 2016/06/01
// Updated by yas 2016/05/31
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Created by yas 2016/04/18

namespace Drupal\aws_cloud\Aws\Ec2;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an Instance entity.
 *
 * @ingroup aws_cloud
 */
interface InstanceInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * {@inheritdoc}
   */
  public function cloud_context();

  /**
   * {@inheritdoc}
   */
  public function instance_id();

  /**
   * {@inheritdoc}
   */
  public function instance_type();

  /**
   * {@inheritdoc}
   */
  public function availability_zone();

  /**
   * {@inheritdoc}
   */
  public function instance_state();

  /**
   * {@inheritdoc}
   */
  public function public_dns();

  /**
   * {@inheritdoc}
   */
  public function public_ip();

  /**
   * {@inheritdoc}
   */
  public function elastic_ip();

  /**
   * {@inheritdoc}
   */
  public function private_dns();

  /**
   * {@inheritdoc}
   */
  public function private_ips();

  /**
   * {@inheritdoc}
   */
  public function private_secondary_ip();

  /**
   * {@inheritdoc}
   */
  public function key_pair_name();

  /**
   * {@inheritdoc}
   */
  public function is_monitoring();

  /**
   * {@inheritdoc}
   */
  public function monitoring();

  /**
   * {@inheritdoc}
   */
  public function setMonitoring($is_monitoring);

  /**
   * {@inheritdoc}
   */
  public function launched();

  /**
   * {@inheritdoc}
   */
  public function security_groups();

  /**
   * {@inheritdoc}
   */
  public function vpc_id();

  /**
   * {@inheritdoc}
   */
  public function subnet_id();

  /**
   * {@inheritdoc}
   */
  public function network_interfaces();

  /**
   * {@inheritdoc}
   */
  public function source_dest_check();

  /**
   * {@inheritdoc}
   */
  public function ebs_optimized();

  /**
   * {@inheritdoc}
   */
  public function root_device_type();

  /**
   * {@inheritdoc}
   */
  public function root_device();

  /**
   * {@inheritdoc}
   */
  public function block_devices();

  /**
   * {@inheritdoc}
   */
  public function scheduled_events();

  /**
   * {@inheritdoc}
   */
  public function image_id();

  /**
   * {@inheritdoc}
   */
  public function platform();

  /**
   * {@inheritdoc}
   */
  public function iam_role();

  /**
   * {@inheritdoc}
   */
  public function owner();

  /**
   * {@inheritdoc}
   */
  public function termination_protection();

  /**
   * {@inheritdoc}
   */
  public function lifecycle();

  /**
   * {@inheritdoc}
   */
  public function alarm_status();

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
  public function placement_group();

  /**
   * {@inheritdoc}
   */
  public function virtualization();

  /**
   * {@inheritdoc}
   */
  public function reservation();

  /**
   * {@inheritdoc}
   */
  public function ami_launch_index();

  /**
   * {@inheritdoc}
   */
  public function tenancy();

  /**
   * {@inheritdoc}
   */
  public function host_id();

  /**
   * {@inheritdoc}
   */
  public function affinity();

  /**
   * {@inheritdoc}
   */
  public function state_transition_reason();

  /**
   * {@inheritdoc}
   */
  public function login_username();

  /**
   * {@inheritdoc}
   */
  public function cloud_type();

  /**
   * {@inheritdoc}
   */
  public function user_data();

  /**
   * {@inheritdoc}
   */
  public function min_count();

  /**
   * {@inheritdoc}
   */
  public function max_count();

  /**
   * {@inheritdoc}
   */
  public function setInstanceId($instance_id = '');


  /**
   * {@inheritdoc}
   */
  public function setInstanceState($state = '');

  /**
   * {@inheritdoc}
   */
  public function setElasticIp($elastic_ip = '');

  /**
   * {@inheritdoc}
   */
  public function setPublicIp($public_ip = '');

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
  public function setName($name);

}
