<?php

namespace Drupal\aws_cloud\Service;

use Aws\Ec2\Ec2Client;
use Aws\Ec2\Exception\Ec2Exception;
use Drupal\aws_cloud\Entity\Ec2\ElasticIp;
use Drupal\aws_cloud\Entity\Ec2\Image;
use Drupal\aws_cloud\Entity\Ec2\Instance;
use Drupal\aws_cloud\Entity\Ec2\KeyPair;
use Drupal\aws_cloud\Entity\Ec2\NetworkInterface;
use Drupal\aws_cloud\Entity\Ec2\SecurityGroup;
use Drupal\aws_cloud\Entity\Ec2\Volume;
use Drupal\aws_cloud\Entity\Ec2\Snapshot;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;


/**
 * AwsEc2Service service interacts with the AWS EC2 api.
 */
class AwsEc2Service implements AwsEc2ServiceInterface {

  use StringTranslationTrait;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */

  protected $queryFactory;

  /**
   * @var string cloud_context
   */
  private $cloud_context;

  /**
   * The config storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $config_storage;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The config factory.
   *
   * Subclasses should use the self::config() method, which may be overridden to
   * address specific needs when loading config, rather than this property
   * directly. See \Drupal\Core\Form\ConfigFormBase::config() for an example of
   * this.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * TRUE to run the operation.  FALSE to run the operation in validation mode
   * @var boolean
   */
  private $dryRun;

  /**
   * Constructs a new AwsEc2Service object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   An entity type manager instance
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   A logger instance.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A configuration factory
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   The messenger service.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   The entity_query object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, Messenger $messenger, TranslationInterface $string_translation, QueryFactory $query_factory) {
    // setup the entity type manager for querying entities
    $this->entityTypeManager = $entity_type_manager;
    // setup the cloud_context configs
    $this->config_storage = $this->entityTypeManager->getStorage('cloud_context');
    // setup the logger
    $this->logger = $logger_factory->get('aws_ec2_service');
    // setup the configuration factory
    $this->configFactory = $config_factory;
    // setup the dryRun flag
    $this->dryRun = $this->configFactory->get('aws_cloud.settings')->get('aws_cloud_test_mode');
    // setup the messenger
    $this->messenger = $messenger;
    // setup the $this->t()
    $this->stringTranslation = $string_translation;

    $this->queryFactory = $query_factory;
  }


  /**
   * {@inheritdoc}
   */
  public function setCloudContext($cloud_context) {
    $this->cloud_context = $cloud_context;
  }

  /**
   * Load and return an Ec2Client
   */
  private function getEc2Client() {
    /* @var \Drupal\aws_cloud\Entity\Config\Config $configs */
    $cloud_config = $this->loadCloudConfig();

    try {
      $ec2_client = Ec2Client::factory([
        'credentials' => [
          'key'    => $cloud_config->aws_access_key(),
          'secret' => $cloud_config->aws_secret_key(),
        ],
        'region'   => $cloud_config->region(),
        'version'  => $cloud_config->api_version(),
        'endpoint' => $cloud_config->endpoint(),
      ]);
    }
    catch (\Exception $e) {
      $ec2_client = NULL;
      $this->logger->error($e->getMessage());
    }

    return $ec2_client;
  }

  /**
   * @param String $operation
   *   The operation to perform
   * @param array $params
   *   An array of parameters
   * @return array or null
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  private function execute($operation, array $params = []) {
    $results = NULL;

    if (empty($params)) {
      throw new AwsEc2ServiceException(sprintf("No parameters passed for operation %s", ['%s' => $operation]));
    }

    $ec2_client = $this->getEc2Client();
    if ($ec2_client == NULL) {
      throw new AwsEc2ServiceException('No Ec2 Client found.  Cannot perform API operations');
    }

    try {
      $command = $ec2_client->getCommand($operation, $params);
      $results = $ec2_client->execute($command);
    }
    catch (Ec2Exception $e) {
      $this->messenger->addError($this->t('Error: The operation "@operation" could not be performed.', [
        '@operation' => $operation,
      ]));

      $this->messenger->addError($this->t('Error Info: @error_info', [
        '@error_info' => $e->getAwsErrorCode(),
      ]));

      $this->messenger->addError($this->t('Error from: @error_type-side', [
        '@error_type' => $e->getAwsErrorType(),
      ]));

      $this->messenger->addError($this->t('Status Code: @status_code', [
        '@status_code' => $e->getStatusCode(),
      ]));

      $this->messenger->addError($this->t('Message: @msg', ['@msg' => $e->getAwsErrorMessage()]));

    }
    return $results;
  }

  /**
   * Return the cloud configuration object
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\aws_cloud\Service\AwsEc2ServiceException
   */
  private function loadCloudConfig() {
    if (!isset($this->cloud_context)) {
      throw new AwsEc2ServiceException("Cloud Context not set.  Cannot load cloud configuration");
    }
    return $this->config_storage->load($this->cloud_context);
  }

  /**
   * {@inheritdoc}
   */
  public function describeInstances($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeInstances', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeImages($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeImages', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeSecurityGroups($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeSecurityGroups', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeNetworkInterfaceList($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeNetworkInterfaces', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeAddresses($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeAddresses', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeSnapshots($params = []) {
    $params += $this->getDefaultParameters();
    $params['RestorableByUserIds'] = [$this->loadCloudConfig()->user_id()];
    $results = $this->execute('DescribeSnapshots', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeKeyPairs($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeKeyPairs', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeVolumes($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeVolumes', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function describeAvailabilityZones($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DescribeAvailabilityZones', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function terminateInstance($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('TerminateInstances', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSecurityGroup($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DeleteSecurityGroup', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteNetworkInterface($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DeleteNetworkInterface', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function releaseAddress($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('ReleaseAddress', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteKeyPair($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DeleteKeyPair', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteVolume($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DeleteVolume', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSnapshot($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('DeleteSnapshot', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function runInstances($params = []) {
    $params += $this->getDefaultParameters();
    $results = $this->execute('RunInstances', $params);
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function updateInstances() {
    $timestamp = $this->getTimestamp();

    $updated = FALSE;
    $entity_type = 'aws_cloud_instance';

    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);

    // call the api and get all instances
    $result = $this->describeInstances();

    if ($result != NULL) {

      // loop through the reservations and store each one as an Instance entity
      foreach ($result['Reservations'] as $reservation) {

        foreach ($reservation['Instances'] as $instance) {
          $instanceName = '';
          foreach ($instance['Tags'] as $tag) {
            if ($tag['Key'] == 'Name') {
              $instanceName = $tag['Value'];
            }
          }
          $security_groups = [];
          foreach ($instance['SecurityGroups'] as $security_group) {
            $security_groups[] = $security_group['GroupName'];
          }

          $entity_id = $this->getEntityId($entity_type, 'instance_id', $instance['InstanceId']);

          // Skip if $entity already exists, by updating 'refreshed' time.
          if (!empty($entity_id)) {
            $entity = Instance::load($entity_id);
            $entity->setInstanceState($instance['State']['Name']);
            $entity->setElasticIp($instance['elastic_ip']);
            $entity->setRefreshed($timestamp);
            $entity->save();
            continue;
          }

          $entity = Instance::create([
            'cloud_context' => $this->cloud_context,
            'name' => !empty($instanceName) ? $instanceName : $instance['InstanceId'],
            'owner' => $reservation['OwnerId'],
            'security_groups' => implode(', ', $security_groups),
            'instance_id' => $instance['InstanceId'],
            'instance_type' => $instance['InstanceType'],
            'availability_zone' => $instance['Placement']['AvailabilityZone'],
            'tenancy' => $instance['Placement']['tenancy'],
            'instance_state' => $instance['State']['Name'],
            'public_dns' => $instance['PublicDnsName'],
            'public_ip' => $instance['PublicIpAddress'],
            'private_dns' => $instance['PrivateDnsName'],
            'private_ips' => $instance['PrivateIpAddress'],
            'key_pair_name' => $instance['KeyName'],
            'is_monitoring' => $instance['Monitoring']['State'],
            'vpc_id' => $instance['VpcId'],
            'subnet_id' => $instance['SubnetId'],
            'source_dest_check' => $instance['SourceDestCheck'],
            'ebs_optimized' => $instance['EbsOptimized'],
            'root_device_type' => $instance['RootDeviceType'],
            'root_device' => $instance['RootDeviceName'],
            'image_id' => $instance['ImageId'],
            'placement_group' => $instance['Placement']['GroupName'],
            'virtualization' => $instance['VirtualizationType'],
            'reservation' => $instance['ReservationId'],
            'ami_launch_index' => $instance['AmiLaunchIndex'],
            'host_id' => $instance['host_id'],
            'affinity' => $instance['affinity'],
            'state_transition_reason' => $instance['StateTransitionReason'],
            'instance_lock' => FALSE,
            'created' => strtotime($instance['LaunchTime']->__toString()),
            'changed' => $timestamp,
            'refreshed' => $timestamp,
          ]);
          $entity->save();
        }
      }
      $updated = TRUE;
    }
    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function updateImages() {
    $timestamp = $this->getTimestamp();

    $updated = FALSE;
    $entity_type = 'aws_cloud_image';

    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);

    $result = $this->describeImages();
    if ($result != NULL) {
      $images = $result['Images'];
      foreach ($images as $image) {
        $block_devices = [];
        foreach ($image['BlockDeviceMappings'] as $block_device) {
          $block_devices[] = $block_device['DeviceName'];
        }

        $entity_id = $this->getEntityId($entity_type, 'image_id', $image['ImageId']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = Image::load($entity_id);
          $entity->setRefreshed($timestamp);
          $entity->save();
          continue;
        }

        $entity = Image::create([
          // $cloud_context,.
          'cloud_context'       => $this->cloud_context,
          'image_id'            => $image['ImageId'],
          'owner'               => $image['OwnerId'],
          'architecture'        => $image['Architecture'],
          'virtualization_type' => $image['VirtualizationType'],
          'root_device_type'    => $image['RootDeviceType'],
          'root_device_name'    => $image['RootDeviceName'],
          'ami_name'            => $image['Name'],
          'kernel_id'           => $image['KernelId'],
          'ramdisk_id'          => $image['RamdiskId'],
          'image_type'          => $image['ImageType'],
          'product_code'        => $image['product_code'],
          'source'              => $image['ImageLocation'],
          'state_reason'        => $image['StateReason']['Message'],
          'platform'            => $image['Platform'],
          'description'         => $image['Description'],
          'visibility'          => $image['Public'],
          'block_devices'       => implode(', ', $block_devices),
          'created'             => strtotime($image['CreationDate']),
          'changed'             => $timestamp,
          'refreshed'           => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }
    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function updateSecurityGroups() {
    $timestamp = $this->getTimestamp();
    $updated = FALSE;
    $entity_type = 'aws_cloud_security_group';

    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);

    $result = $this->describeSecurityGroups();
    if ($result != NULL) {
      // 3. Add objects
      $security_groups = $result['SecurityGroups'];
      foreach ($security_groups as $security_group) {

        $entity_id = $this->getEntityId($entity_type, 'group_id', $security_group['GroupId']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = SecurityGroup::load($entity_id);
          $entity->setRefreshed($timestamp);
          $entity->save();
          continue;
        }

        $entity = SecurityGroup::create([
          // $cloud_context,.
          'cloud_context'     => $this->cloud_context,
          'name'              => !empty($security_group['GroupName']) ? $security_group['GroupName'] : $security_group['GroupId'],
          'group_id'          => $security_group['GroupId'],
          'group_name'        => $security_group['GroupName'],
          'group_description' => $security_group['Description'],
          'vpc_id'            => $security_group['VpcId'],
          'owner_id'          => $security_group['OwnerId'],
          'created'           => $timestamp,
          'changed'           => $timestamp,
          'refreshed'         => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }
    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function updateNetworkInterfaces() {
    $timestamp = $this->getTimestamp();
    $updated = FALSE;
    $entity_type = 'aws_cloud_network_interface';

    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);

    $result = $this->describeNetworkInterfaceList();
    if ($result != NULL) {
      $network_interfaces = $result['NetworkInterfaces'];
      foreach ($network_interfaces as $network_interface) {
        $private_ip_addresses = [];
        foreach ($network_interface['PrivateIpAddresses'] as $private_ip_address) {
          $private_ip_addresses[] = $private_ip_address['PrivateIpAddress'];
        }

        $security_groups = [];
        foreach ($network_interface['Groups'] as $security_group) {
          $security_groups[] = $security_group['GroupName'];
        }

        $entity_id = $this->getEntityId($entity_type, 'network_interface_id', $network_interface['NetworkInterfaceId']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = NetworkInterface::load($entity_id);
          $entity->setRefreshed($timestamp);
          $entity->save();

          continue;
        }

        $entity = NetworkInterface::create([
          'cloud_context'         => $this->cloud_context,
          'name'                  => $network_interface['NetworkInterfaceId'],
          'network_interface_id'  => $network_interface['NetworkInterfaceId'],
          'vpc_id'                => $network_interface['VpcId'],
          'mac_address'           => $network_interface['MacAddress'],
          'security_groups'       => implode(', ', $security_groups),
          'status'                => $network_interface['Status'],
          'private_dns'           => $network_interface['PrivateDnsName'],
          'primary_private_ip'    => $network_interface['PrivateIpAddress'],
          'secondary_private_ips' => implode(', ', $private_ip_addresses),
          'attachment_id'         => $network_interface['Attachment']['AttachmentId'],
          'attachment_owner'      => $network_interface['Attachment']['InstanceOwnerId'],
          'attachment_status'     => $network_interface['Attachment']['Status'],
          'owner_id'              => $network_interface['OwnerId'],
          'association_id'        => $network_interface['Association']['AssociationId'],
          'subnet_id'             => $network_interface['SubnetId'],
          'description'           => $network_interface['Description'],
          'public_ips'            => $network_interface['Association']['PublicIp'],
          'source_dest_check'     => $network_interface['SourceDestCheck'],
          'instance_id'           => $network_interface['Attachment']['InstanceId'],
          'device_index'          => $network_interface['Attachment']['DeviceIndex'],
          'delete_on_termination' => $network_interface['Attachment']['DeleteOnTermination'],
          'allocation_id'         => $network_interface['Association']['AllocationId'],
          'owner'                 => $network_interface['Attachment']['InstanceOwnerId'],
          'created'               => $timestamp,
          'changed'               => $timestamp,
          'refreshed'             => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }
    return $updated;

  }

  /**
   * {@inheritdoc}
   */
  public function updateElasticIp() {
    $timestamp = $this->getTimestamp();
    $updated = FALSE;
    $entity_type = 'aws_cloud_elastic_ip';
    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);

    $result = $this->describeAddresses();

    if ($result != NULL) {
      $elastic_ips = $result['Addresses'];
      foreach ($elastic_ips as $elastic_ip) {

        $entity_id = $this->getEntityId($entity_type, 'public_ip', $elastic_ip['PublicIp']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = ElasticIp::load($entity_id);
          $entity->setRefreshed($timestamp);
          $entity->save();
          continue;
        }

        $entity = ElasticIp::create([
          'cloud_context'           => $this->cloud_context,
          'name'                    => $elastic_ip['PublicIp'],
          'public_ip'               => $elastic_ip['PublicIp'],
          'instance_id'             => !empty($elastic_ip['InstanceId']) ? $elastic_ip['InstanceId'] : '',
          'network_interface_id'    => !empty($elastic_ip['NetworkInterfaceId']) ? $elastic_ip['NetworkInterfaceId'] : '',
          'private_ip_address'      => !empty($elastic_ip['PrivateIpAddress']) ? $elastic_ip['PrivateIpAddress'] : '',
          'network_interface_owner' => !empty($elastic_ip['NetworkInterfaceOwnerId']) ? $elastic_ip['NetworkInterfaceOwnerId'] : '',
          'allocation_id'           => !empty($elastic_ip['AllocationId']) ? $elastic_ip['AllocationId'] : '',
          'association_id'          => !empty($elastic_ip['AssociationId']) ? $elastic_ip['AssociationId'] : '',
          'domain'                  => !empty($elastic_ip['Domain']) ? $elastic_ip['Domain'] : '',
          'created'                 => $timestamp,
          'changed'                 => $timestamp,
          'refreshed'               => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }
    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function updateKeyPairs() {
    $timestamp = $this->getTimestamp();
    $updated = FALSE;

    $entity_type = 'aws_cloud_key_pair';
    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);

    $result = $this->describeKeyPairs();
    if ($result != NULL) {
      // 3. Add objects.
      $key_pairs = $result['KeyPairs'];
      foreach ($key_pairs as $key_pair) {

        $entity_id = $this->getEntityId($entity_type, 'key_pair_name', $key_pair['KeyName']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = KeyPair::load($entity_id);
          $entity->setRefreshed($timestamp);
          $entity->save();
          continue;
        }

        $entity = KeyPair::create([
          // $cloud_context,.
          'cloud_context'   => $this->cloud_context,
          'key_pair_name'   => $key_pair['KeyName'],
          'key_fingerprint' => $key_pair['KeyFingerprint'],
          'created'         => $timestamp,
          'changed'         => $timestamp,
          'refreshed'       => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }
    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function updateVolumes() {
    $timestamp = $this->getTimestamp();
    $updated = FALSE;

    $entity_type = 'aws_cloud_volume';
    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);
    $result = $this->describeVolumes();

    if ($result != NULL) {
      $volumes = $result['Volumes'];
      foreach ($volumes as $volume) {
        $attachments = [];
        foreach ($volume['Attachments'] as $attachment) {
          $attachments[] = $attachment['InstanceId'];
        }

        $entity_id = $this->getEntityId($entity_type, 'volume_id', $volume['VolumeId']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = Volume::load($entity_id);
          $entity->setRefreshed($timestamp);
          $entity->save();

          continue;
        }

        $entity = Volume::create([
          // $cloud_context,.
          'cloud_context'          => $this->cloud_context,
          'name'                   => $volume['VolumeId'],
          'volume_id'              => $volume['VolumeId'],
          'size'                   => $volume['Size'],
          'state'                  => $volume['State'],
          'volume_status'          => $volume['VirtualizationType'],
          'attachment_information' => implode(', ', $attachments),
          'volume_type'            => $volume['VolumeType'],
          'iops'                   => $volume['Iops'],
          'snapshot'               => $volume['SnapshotId'],
          'availability_zone'      => $volume['AvailabilityZone'],
          'encrypted'              => $volume['Encrypted'],
          'kms_key_id'             => $volume['KmsKeyId'],
          'created'                => strtotime($volume['CreateTime']),
          'changed'                => $timestamp,
          'refreshed'              => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }

    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function updateSnapshots() {
    $timestamp = $this->getTimestamp();
    $updated = FALSE;

    $entity_type = 'aws_cloud_snapshot';
    // clear out entities that are expired
    $this->clearEntities($entity_type, $timestamp);
    $result = $this->describeSnapshots();
    if ($result != NULL) {
      $snapshots = $result['Snapshots'];
      foreach ($snapshots as $snapshot) {

        $entity_id = $this->getEntityId($entity_type, 'snapshot_id', $snapshot['SnapshotId']);

        // Skip if $entity already exists, by updating 'refreshed' time.
        if (!empty($entity_id)) {
          $entity = Snapshot::load($entity_id);
          $entity->setRefreshed($timestamp);

          continue;
        }

        $entity = Snapshot::create([
          'cloud_context' => $this->cloud_context,
          'snapshot_id'   => $snapshot['SnapshotId'],
          'size'          => $snapshot['VolumeSize'],
          'description'   => $snapshot['Description'],
          'status'        => $snapshot['State'],
          'volume_id'     => $snapshot['VolumeId'],
          'progress'      => $snapshot['Progress'],
          'encrypted'     => $snapshot['Encrypted'],
          'kms_key_id'    => $snapshot['KmsKeyId'],
          'owner_id'      => $snapshot['OwnerId'],
          'owner_aliases' => $snapshot['OwnerAlias'],
          'state_message' => $snapshot['StateMessage'],
          'created'       => strtotime($snapshot['StartTime']),
          'changed'       => $timestamp,
          'refreshed'     => $timestamp,
        ]);
        $entity->save();
      }
      $updated = TRUE;
    }

    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailabilityZones() {
    $zones = [];
    $results = $this->describeAvailabilityZones();
    if ($results != NULL) {
      foreach (array_column($results['AvailabilityZones'], 'ZoneName') as $key => $availability_zone) {
        $availability_zones[$availability_zone] = $availability_zone;
      }
    }
    return $zones;
  }
  /**
   * Helper method to get the current timestamp
   * @return int
   */
  private function getTimestamp() {
    return time();
  }


  /**
   * Setup the default parameters that all API calls will need
   * @return array
   */
  private function getDefaultParameters() {
    return [
      'DryRun' => $this->dryRun,
    ];
  }

  /**
   * Helper method to delete entities.
   * @param string $entity_type
   *   Entity Type
   * @param $entity_ids
   *   Array of entity ids
   */
  private function deleteEntities($entity_type, $entity_ids) {
    $entities = $this->entityTypeManager->getStorage($entity_type)->loadMultiple($entity_ids);
    $this->entityTypeManager->getStorage($entity_type)->delete($entities);
  }

  /**
   * @param string $entity_type
   *   Entity Type
   * @return array|int
   *   Array of entities
   */
  private function clearEntities($entity_type, $timestamp) {
    $entity_ids = $this->queryFactory->get($entity_type)
      ->condition('refreshed', $timestamp, '<')
      ->execute();
    if (count($entity_ids)) {
      $this->deleteEntities($entity_type, $entity_ids);
    }
  }

  /**
   * Helper method to load an entity using parameters
   *
   * @param string $entity_type
   *  Entity Type
   * @param string $id_field
   *  entity id field
   * @param string $id_value
   *  entity id value
   * @return int Entity id
   *  Entity id
   */
  private function getEntityId($entity_type, $id_field, $id_value) {
    return array_shift($this->queryFactory->get($entity_type)
      ->condition($id_field, $id_value)
      ->execute());
  }

}
