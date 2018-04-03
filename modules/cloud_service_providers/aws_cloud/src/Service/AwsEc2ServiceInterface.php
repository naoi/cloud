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
   *  @return boolean
   *    indicates success so failure
   */
  public function updateImages();

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
}
