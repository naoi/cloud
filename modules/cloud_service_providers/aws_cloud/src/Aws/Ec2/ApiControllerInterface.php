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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container);

  /**
   * {@inheritdoc}
   */
  public function getEc2Client(string $cloud_context);

  /**
   * ;@inheritdoc}.
   */
  public function getMockhandlerResult($params = []);

  /**
   * ;@inheritdoc}.
   */
  public function launchInstance(Instance $instance);

  /**
   * Launch an instance using a param array
   */
  public function launchUsingParams($cloud_context, $params = []);

  /**
   * ;@inheritdoc}.
   */
  public function createImage(Image $image);

  /**
   * ;@inheritdoc}.
   */
  public function createSecurityGroup(SecurityGroup $security_group);

  /**
   * ;@inheritdoc}.
   */
  public function createNetworkInterface(NetworkInterface $network_interface);

  /**
   * ;@inheritdoc}.
   */
  public function createElasticIp(ElasticIp $elastic_ip);

  /**
   * @inheritdoc}
   */
  public function createKeyPair(KeyPair $key_pair);

  /**
   * @inheritdoc}
   */
  public function createVolume(Volume $volume);

  /**
   * @inheritdoc}
   */
  public function createSnapshot(Snapshot $snapshot);

  /**
   * {@inheritdoc}
   */
  public function updateInstanceList(ConfigInterface $cloud_context);

  /**
   * {@inheritdoc}
   */
  public function updateImageList(ConfigInterface $cloud_context);

  /**
   * {@inheritdoc}
   */
  public function updateSecurityGroupList(ConfigInterface $cloud_context);

  /**
   * {@inheritdoc}
   */
  public function updateNetworkInterfaceList(ConfigInterface $cloud_context);

  /**
   * {@inheritdoc}
   */
  public function updateElasticIpList(ConfigInterface $cloud_context);

  /**
   * @inheritdoc}
   */
  public function updateKeyPairList(ConfigInterface $cloud_context);

  /**
   * @inheritdoc}
   */
  public function updateVolumeList(ConfigInterface $cloud_context);

  /**
   * @inheritdoc}
   */
  public function updateSnapshotList(ConfigInterface $cloud_context);

  /**
   * {@inheritdoc}
   */
  public function terminateInstance(Instance $instance);

  /**
   * {@inheritdoc}
   */
  public function deregisterImage(Image $image);

  /**
   * {@inheritdoc}
   */
  public function deleteSecurityGroup(SecurityGroup $security_group);

  /**
   * {@inheritdoc}
   */
  public function deleteNetworkInterface(NetworkInterface $network_interface);

  /**
   * {@inheritdoc}
   */
  public function deleteElasticIp(ElasticIp $elastic_ip);

  /**
   * @inheritdoc}
   */
  public function deleteKeyPair(KeyPair $key_pair);

  /**
   * @inheritdoc}
   */
  public function deleteVolume(Volume $volume);

  /**
   * @inheritdoc}
   */
  public function deleteSnapshot(Snapshot $snapshot);

  /**
   * @inheritdoc}
   */
  public function getAvailabilityZones(ConfigInterface $cloud_context);

  /**
   * @inheritdoc}
   */
  public function getVpcs(ConfigInterface $cloud_context);

  /**
   * @inheritdoc}
   */
  public function execute(string $cloud_context = '', string $command = '', array $params = []);
}
