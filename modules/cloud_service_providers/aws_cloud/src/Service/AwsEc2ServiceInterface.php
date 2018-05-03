<?php

namespace Drupal\aws_cloud\Service;

/**
 * Interface AwsEc2ServiceInterface.
 */
interface AwsEc2ServiceInterface {

  /**
   * Set the cloud context
   * @param string $cloud_context
   * @return mixed
   */
  public function setCloudContext($cloud_context);

  /**
   * Calls the Ec2 API endpoint AllocateAddress.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of ElasticIps or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function allocateAddress($params = []);

  /**
   * Calls the Ec2 API endpoint CreateImage.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of CreateImage or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function createImage($params = []);

  /**
   * Calls the Ec2 API endpoint Create Key Pair.
   *
   * @param array $params
   *   Parameters array to send to API
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function createKeyPair($params = []);

  /**
   * Calls the Ec2 API endpoint Create Network Interface.
   *
   * @param array $params
   *   Parameters array to send to API
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function createNetworkInterface($params = []);

  /**
   * Calls the Ec2 API endpoint Create Volume.
   *
   * @param array $params
   *   Parameters array to send to API
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function createVolume($params = []);

  /**
   * Calls the Ec2 API endpoint Create Snapshot.
   *
   * @param array $params
   *   Parameters array to send to API
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function createSnapshot($params = []);

  /**
   * Calls the Ec2 API endpoint Create Security Group.
   *
   * @param array $params
   *   Parameters array to send to API
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function createSecurityGroup($params = []);

  /**
   * Calls the Ec2 API endpoint DeregisterImage.
   *
   * @param array $params
   *   Parameters array to send to API
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function deregisterImage($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeInstances.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of Instances or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeInstances($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeImages.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of Images or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeImages($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeSecurityGroups.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of Images or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeSecurityGroups($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeNetworkInterfaces.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of Images or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeNetworkInterfaceList($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeAddresses.
   *
   * @param array $params
   *   Parameters array to send to API
   * @return Array of Images or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeAddresses($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeSnapshots.  Only snapshots restorable by the user
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeSnapshots($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeKeyPairs.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeKeyPairs($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeVolumes.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeVolumes($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeAvailabilityZones.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of zones or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeAvailabilityZones($params = []);

  /**
   * Calls the Ec2 API endpoint DescribeVpcs.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of VPCs or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function describeVpcs($params = []);

  /**
   * Calls the Ec2 API endpoint ImportKeyPair.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of VPCs or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function importKeyPair($params = []);

  /**
   * Calls the Ec2 API endpoint TerminateInstances.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function terminateInstance($params = []);

  /**
   * Calls the Ec2 API endpoint DeleteSecurityGroup.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function deleteSecurityGroup($params = []);

  /**
   * Calls the Ec2 API endpoint DeleteNetworkInterface.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function deleteNetworkInterface($params = []);

  /**
   * Calls the Ec2 API endpoint ReleaseAddress.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function releaseAddress($params = []);

  /**
   * Calls the Ec2 API endpoint DeleteKeyPair.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function deleteKeyPair($params = []);

  /**
   * Calls the Ec2 API endpoint DeleteVolume.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function deleteVolume($params = []);

  /**
   * Calls the Ec2 API endpoint DeleteSnapshot.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function deleteSnapshot($params = []);

  /**
   * Calls the Ec2 API endpoint RunInstances.
   * are returned.
   *
   * @param array $params
   *  Parameters array to send to API
   * @return Array of snapshots or NULL if there is an error
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  public function runInstances($params = []);

  /**
   * Update the Ec2 Instances.  Delete old Instance entities, query the api
   * for updated entities and store them as Instance entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateInstances();

  /**
   * Update the Ec2 Images.  Delete old Images entities, query the api
   * for updated entities and store them as Images entities.
   *
   * @params array $params
   *   Optional parameters array
   * @params boolean $clear
   *   TRUE to delete images entities before importing
   *  @return boolean | int
   *    FALSE if nothing is updated.  Number of images imported returned as
   *    integer if successful
   */
  public function updateImages($params = [], $clear = TRUE);

  /**
   * Update the Ec2 Security Groups.  Delete old Security Groups entities,
   * query the api for updated entities and store them as Security Groups entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateSecurityGroups();

  /**
   * Update the Ec2 Network Interfaces.  Delete old Network Interfaces entities,
   * query the api for updated entities and store them as Network Interfaces entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateNetworkInterfaces();

  /**
   * Update the Ec2 Elastic Ips.  Delete old Network Interfaces entities,
   * query the api for updated entities and store them as Network Interfaces entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateElasticIp();

  /**
   * Update the Ec2 Key Pairs.  Delete old Key Pairs entities,
   * query the api for updated entities and store them as Key Pairs entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateKeyPairs();

  /**
   * Update the Ec2 Volumes.  Delete old Volumes entities,
   * query the api for updated entities and store them as Volumes entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateVolumes();

  /**
   * Update the Ec2 snapshots.  Delete old snapshots entities,
   * query the api for updated entities and store them as snapshots entities.
   *
   *  @return boolean
   *    indicates success so failure
   */
  public function updateSnapshots();

  /**
   * Method gets all the availability zones in a particular cloud context.
   * @return array
   */
  public function getAvailabilityZones();

  /**
   * Method gets all the VPCs in a particular cloud context.
   * @return array
   */
  public function getVpcs();

}
