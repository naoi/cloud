<?php

// Updated by yas 2016/09/11
// Updated by yas 2016/09/06
// Updated by yas 2016/06/04
// Updated by yas 2016/06/02
// Updated by yas 2016/06/01
// Created by yas 2016/05/28.
namespace Drupal\aws_cloud\Aws\Ec2;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\aws_cloud\Aws\Config\ConfigInterface;
use Drupal\aws_cloud\Entity\Ec2\Instance;
use Drupal\aws_cloud\Entity\Ec2\Image;
use Drupal\aws_cloud\Entity\Ec2\SecurityGroup;
use Drupal\aws_cloud\Entity\Ec2\NetworkInterface;
use Drupal\aws_cloud\Entity\Ec2\ElasticIp;
use Drupal\aws_cloud\Entity\Ec2\KeyPair;
use Drupal\aws_cloud\Entity\Ec2\Volume;
use Drupal\aws_cloud\Entity\Ec2\Snapshot;


/**
 * {@inheritdoc}
 */
interface ApiControllerInterface {


  /**
   * Update all instances in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateInstanceList(ConfigInterface $cloud_context);

  /**
   * Update all images in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateImageList(ConfigInterface $cloud_context);

  /**
   * Update all security groups in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateSecurityGroupList(ConfigInterface $cloud_context);

  /**
   * Update all network interfaces in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateNetworkInterfaceList(ConfigInterface $cloud_context);

  /**
   * Update all elastic ips in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateElasticIpList(ConfigInterface $cloud_context);

  /**
   * Update all key pairs in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateKeyPairList(ConfigInterface $cloud_context);

  /**
   * Update all volumes in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateVolumeList(ConfigInterface $cloud_context);

  /**
   * Update all snapshots in particular cloud region
   *
   * @param \Drupal\aws_cloud\Aws\Config\ConfigInterface $cloud_context
   * @return mixed
   */
  public function updateSnapshotList(ConfigInterface $cloud_context);


}
