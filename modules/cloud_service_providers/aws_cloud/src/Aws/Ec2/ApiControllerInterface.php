<?php

// Updated by yas 2016/09/11
// Updated by yas 2016/09/06
// Updated by yas 2016/06/04
// Updated by yas 2016/06/02
// Updated by yas 2016/06/01
// Created by yas 2016/05/28.
namespace Drupal\aws_cloud\Aws\Ec2;


/**
 * {@inheritdoc}
 */
interface ApiControllerInterface {

  /**
   * Update all instances in particular cloud region
   * @param String $cloud_context
   * @return mixed
   */
  public function updateInstanceList($cloud_context);

  /**
   * Update all images in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateImageList($cloud_context);

  /**
   * Update all security groups in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateSecurityGroupList($cloud_context);

  /**
   * Update all network interfaces in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateNetworkInterfaceList($cloud_context);

  /**
   * Update all elastic ips in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateElasticIpList($cloud_context);

  /**
   * Update all key pairs in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateKeyPairList($cloud_context);

  /**
   * Update all volumes in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateVolumeList($cloud_context);

  /**
   * Update all snapshots in particular cloud region
   *
   * @param String $cloud_context
   * @return mixed
   */
  public function updateSnapshotList($cloud_context);


}
