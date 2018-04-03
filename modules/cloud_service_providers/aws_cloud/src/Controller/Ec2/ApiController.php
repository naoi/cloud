<?php

// Updated by yas 2016/09/11
// Updated by yas 2016/09/07
// Updated by yas 2016/09/06
// Updated by yas 2016/09/05
// Updated by yas 2016/09/04
// Updated by yas 2016/07/06
// Updated by yas 2016/07/05
// Updated by yas 2016/07/03
// Updated by yas 2016/06/23
// Updated by yas 2016/06/13
// Updated by yas 2016/06/12
// Updated by yas 2016/06/11
// Updated by yas 2016/06/05
// Updated by yas 2016/06/04
// Updated by yas 2016/06/03
// Updated by yas 2016/06/02
// Updated by yas 2016/06/01
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// Updated by yas 2016/05/28
// Updated by yas 2016/05/27
// Updated by yas 2016/05/26
// Updated by yas 2016/05/25
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Created by yas 2016/04/21.
namespace Drupal\aws_cloud\Controller\Ec2;

use Aws\Ec2\Ec2Client;
use Aws\Ec2\Exception\Ec2Exception;
use Aws\MockHandler;
use Aws\Result;
use Drupal\aws_cloud\Aws\Config\ConfigInterface;
use Drupal\aws_cloud\Aws\Ec2\ApiControllerInterface;
use Drupal\aws_cloud\Entity\Config\Config;
use Drupal\aws_cloud\Entity\Ec2\ElasticIp;
use Drupal\aws_cloud\Entity\Ec2\Image;
use Drupal\aws_cloud\Entity\Ec2\Instance;
use Drupal\aws_cloud\Entity\Ec2\KeyPair;
use Drupal\aws_cloud\Entity\Ec2\NetworkInterface;
use Drupal\aws_cloud\Entity\Ec2\SecurityGroup;
use Drupal\aws_cloud\Entity\Ec2\Snapshot;
use Drupal\aws_cloud\Entity\Ec2\Volume;
use Drupal\aws_cloud\Service\AwsEc2ServiceException;
use Drupal\Component\Utility\Random;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\aws_cloud\Service\AwsEc2ServiceInterface;

//\Drupal::moduleHandler()->loadInclude('cloud', 'inc', 'cloud.constants');

/**
 * {@inheritdoc}
 */
class ApiController extends ControllerBase implements ApiControllerInterface {

  /**
   * {@inheritdoc}
   */
  private $is_test = TRUE;


  /**
   * {@inheritdoc}
   */
//private $is_dryrun = true;  // true || false, _NOT_ TRUE or FALSE
  private $is_dryrun = false;  // true || false, _NOT_ TRUE or FALSE

  /**
   * {@inheritdoc}
   */
  private $now;

  /**
   * @var \Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
   */
  private $awsEc2Service;

  /**
   * This function and the next are part of the dependency injection pattern.
   */
  public function __construct(QueryFactory $entity_query, AwsEc2ServiceInterface $aws_ec2_service) {
    $this->entity_query = $entity_query;
    $this->now = (new \DateTime())->getTimestamp();
    $this->awsEc2Service = $aws_ec2_service;
  }

  /**
   * Dependency Injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      // User the $container to get a query factory object.
      // This object let us create query objects.
      $container->get('entity.query'),
      $container->get('aws_cloud.ec2')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getMockhandlerResult($params = []) {

    $random = new Random();

    return new Result([

        'RunInstances' => [
          'Instances' => [
            [
              // The following parameters are required.
              'InstanceId'         => 'i-' . bin2hex(openssl_random_pseudo_bytes(8)),
              'ImageId'            => !empty($params['ImageId'])        ? $params['ImageId']        : '',
              'KeyName'            => !empty($params['KeyName'])        ? $params['KeyName']        : '',
              'Monitoring'         => !empty($params['Monitoring'])     ? $params['Monitoring']     : '',
              // The following parameters are optional.
              'Placement'          => !empty($params['Placement'])      ? $params['Placement']      : '',
              // SecurityGroups is an array.
              // @FIXME for an array
              'SecurityGroups'     => !empty($params['SecurityGroups']) ? $params['SecurityGroups'] : '',
              'InstanceType'       => !empty($params['InstanceType'])   ? $params['InstanceType']   : '',
              'KernelId'           => !empty($params['KernelId'])       ? $params['KernelId']       : '',
              'RamdiskId'          => !empty($params['RamdiskId'])      ? $params['RamdiskId']      : '',
              'UserData'           => !empty($params['UserData'])       ? $params['UserData']       : '',
              // Or i386.
              'Architecture'       => 'x86_64',
              'EbsOptimized'       => TRUE,
              // Or ovm.
              'Hypervisor'         => 'xen',
              'PublicIpAddress'    => implode('.', [rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255)]),
              'LaunchTime'         => date('Y/m/d H:i:s'),
              // pending | running | shutting-down | terminated | stopping | stopped.
              'State'              => ['Name' => 'running'],
              // Or paravirtual.
              'VirtualizationType' => 'hvm',
              // instance count

            ],
          ],
        ],

        'CreateImage' => [
          'ImageId'     => 'ami-' . bin2hex(openssl_random_pseudo_bytes(4)),
          'Description' => !empty($params['Description']) ? $params['Description'] : '',
        ],

        'CreateSecurityGroup' => [
          'GroupId'   => 'sg-' . bin2hex(openssl_random_pseudo_bytes(4)),
        ],

        'AllocateAddress' => [
          'AllocationId'  => 'allocation_id-' . bin2hex(openssl_random_pseudo_bytes(4)),
          'Domain'        => !empty($params['Domain']) ? $params['Domain'] : '',
          'PublicIp'      => implode('.', [rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255)]),
        ],

        'CreateKeyPair' => [
          // @TODO: Write Tips
          'KeyFingerprint' => implode(':', array_map(function() {
            return bin2hex(openssl_random_pseudo_bytes(1));
          }, array_fill(0, 20, ''))),
          'KeyName'        => !empty($params['KeyName']) ? $params['KeyName'] : '',
          'KeyMaterial'    => bin2hex(openssl_random_pseudo_bytes(255)),
        ],

        'CreateNetworkInterface' => [
          'Description' => !empty($params['Description']) ? $params['Description'] : '',
          'Groups' => [
              [
                'GroupId'   => !empty($params['GroupId'])   ? $params['GroupId']   : '',
                'GroupName' => !empty($params['GroupName']) ? $params['GroupName'] : '',
              ],
        ],

        'NetworkInterfaceId' => 'if-' . bin2hex(openssl_random_pseudo_bytes(1)), 
          'PrivateIpAddress'   => !empty($params['PrivateIpAddress'])   ? $params['PrivateIpAddress'] : '',
          'PrivateIpAddresses' => [
            [
              'Primary'          => !empty($params['Primary'])          ? $params['Primary']          : '',
              'PrivateIpAddress' => !empty($params['PrivateIpAddress']) ? $params['PrivateIpAddress'] : '',
            ],
          ],
          'Status'   => 'available', // 'available|attaching|in-use|detaching' 
          'SubnetId' => !empty($params['SubnetId']) ? $params['SubnetId'] : '',
          'VpcId'    => 'vpc-' . bin2hex(openssl_random_pseudo_bytes(4)), 
          'SecondaryPrivateIpAddressCount' => !empty($params['SecondaryPrivateIpAddressCount'])
                                            ? $params['SecondaryPrivateIpAddressCount'] : '', 
        ],

        'CreateVolume' => [
          'AvailabilityZone' => !empty($params['AvailabilityZone']) ? $params['AvailabilityZone'] : '',
          'CreateTime'       => date('Y/m/d H:i:s'),
          'Encrypted'        => !empty($params['encrypted'])   ? $params['encrypted']  : '', // true || false,
          'Iops'             => !empty($params['Iops'])        ? $params['Iops']       : '',
          'KmsKeyId'         => !empty($params['KmsKeyId'])    ? $params['KmsKeyId']   : '',
          'Size'             => !empty($params['Size'])        ? $params['Size']       : '',
          'SnapshotId'       => !empty($params['SnapshotId'])  ? $params['SnapshotId'] : '',
          'State'            => 'in-use', // 'creating|available|in-use|deleting|deleted|error'
          'VolumeId'         => 'vol-' . bin2hex(openssl_random_pseudo_bytes(4)),
          'VolumeType'       => !empty($params['volume_type']) ? $params['volume_type'] : '', // 'standard|io1|gp2|sc1|st1',
        ],

        'CreateSnapshot' => [
          'Description' => !empty($params['Description']) ? $params['Description'] : '',
          'Encrypted'   => !empty($params['Encrypted']) ? $params['Encrypted'] : '', // true || false,
          'SnapshotId'  => 'snap-' . bin2hex(openssl_random_pseudo_bytes(4)),
          'StartTime'   => date('Y/m/d H:i:s'),
          'State'       => 'completed', // 'pending|completed|error',
          'VolumeId'    => !empty($params['VolumeId'])    ? $params['VolumeId']   : '',
          'VolumeSize'  => !empty($params['VolumeSize'])  ? $params['VolumeSize'] : '',
        ],

        'TerminateInstances' => [
          [
              // The following parameters are required.
            'InstanceId'   =>  !empty($params['InstanceIds'][0]) ? $params['InstanceIds'][0] : '', // @FIXME for InstanceIds => InstancdeId
            'CurrentState' => [
              'Code' => rand(0, 10),
              'Name' => 'terminated', // pending | running | shutting-down | terminated | stopping | stopped
            ],
            'PreviousState' => [
              'Code' => rand(0, 10),
              'Name' => 'shutting-down', // pending | running | shutting-down | terminated | stopping | stopped
            ],
          ],
        ],

        'DeregisterImage' => [
          // The results for this operation are always empty.
        ],

        'DeleteSecurityGroup' => [
          // The results for this operation are always empty.
        ],

        'ReleaseAddress' => [
          // The results for this operation are always empty.
        ],

        'DeleteKeyPair' => [
          // The results for this operation are always empty.
        ],

        'DeleteNetworkInterface' => [
          // The results for this operation are always empty.
        ],

        'DeleteVolume' => [
          // The results for this operation are always empty.
        ],

        'DeleteSnapshot' => [
          // The results for this operation are always empty.
        ],
/* For debug
        'DescribeInstances' => array(
          'Reservations' => array(
            array(
              'ReservationId' => 'r-' . bin2hex(openssl_random_pseudo_bytes(8)),
              'OwnerId' => 143027827854,
              'Groups' => array(),
              'Instances' => array(
                array(
                  'InstanceId' => 'i-'   . bin2hex(openssl_random_pseudo_bytes(8)),
                  'ImageId'    => 'ami-' . bin2hex(openssl_random_pseudo_bytes(4)),
                  'State' => array(
                    'Code' => 16,
                    'Name' => 'running',
                  ),
                  'PrivateDnsName' => 'ip-' . implode('-', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))) . '.ec2.internal',
                  'PublicDnsName' => 'ec2-' . implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))) . '.compute-1.amazonaws.com',
                  'StateTransitionReason' => '',
                  'AmiLaunchIndex' => 0,
                  'ProductCodes' => array(
                    array(
                      'ProductCodeId' => bin2hex(openssl_random_pseudo_bytes(12)),
                      'ProductCodeType' => 'marketplace',
                    ),
                  ),
                  'InstanceType' => 'm3.large',
                  'LaunchTime' => array(
                    'date' => date('Y/m/d H:i:s', $this->now - 24 * 60 * 60 * 14), // Two weeks ago (x14 = randomly hard-corded)
                    'timezone_type' => 2,
                      'timezone' => 'Z',
                  ),
                  'Placement' => array(
                    'AvailabilityZone' => 'us-east-1z',
                    'GroupName' => '',
                    'Tenancy' => 'default',
                  ),
                  'Monitoring' => array(
                    'State' => 'disabled',
                  ),
                  'SubnetId' => 'subnet-' . bin2hex(openssl_random_pseudo_bytes(4)),
                  'VpcId' => 'vpc-' . bin2hex(openssl_random_pseudo_bytes(4)),
                  'PrivateIpAddress' => implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))),
                  'PublicIpAddress' => implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))),
                  'Architecture' => 'x86_64',
                  'RootDeviceType' => 'ebs',
                  'RootDeviceName' => '/dev/sda1',
                  'BlockDeviceMappings' => array(
                    array(
                      'DeviceName' => '/dev/sda1',
                      'Ebs' => array(
                        'VolumeId' => 'vol-' . bin2hex(openssl_random_pseudo_bytes(4)),
                        'Status' => 'attached',
                        'AttachTime' => array(
                          'date' => '2016-05-24 00:21:40.000000',
                          'timezone_type' => 2,
                          'timezone' => 'Z',
                        ),
                        'DeleteOnTermination' => 1,
                      ),
                    ),
                  ),
                  'VirtualizationType' => 'hvm',
                  'ClientToken' =>  bin2hex(openssl_random_pseudo_bytes(4)) . '-' .  bin2hex(openssl_random_pseudo_bytes(2)) . '-' .  bin2hex(openssl_random_pseudo_bytes(2)). '-' .  bin2hex(openssl_random_pseudo_bytes(2)). '-' . bin2hex(openssl_random_pseudo_bytes(4)),
                  'SecurityGroups' => array(
                    array(
                      'GroupName' => 'Ubuntu Server 14-04 LTS -HVM--14-04 LTS 20151117-AutogenByAWSMP-',
                      'GroupId' => 'sg-' . bin2hex(openssl_random_pseudo_bytes(4)),
                    ),
                  ),
                  'SourceDestCheck' => 1,
                  'Hypervisor' => 'xen',
                  'NetworkInterfaces' => array(
                    array(
                      'NetworkInterfaceId' => 'eni-' . bin2hex(openssl_random_pseudo_bytes(4)),
                      'SubnetId' => 'subnet-' . bin2hex(openssl_random_pseudo_bytes(4)),
                      'VpcId' => 'vpc-' . bin2hex(openssl_random_pseudo_bytes(4)),
                      'Description' => '',
                      'OwnerId' => 143027827854,
                      'Status' => 'in-use',
                      'MacAddress' => implode(':', array_map(function() {
                        return bin2hex(openssl_random_pseudo_bytes(1));
                      }, array_fill(0, 6, ''))),
                      'PrivateIpAddress' => implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))),
                      'PrivateDnsName' => 'ip-' . implode('-', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))) . '.ec2.internal',
                      'SourceDestCheck' => 1,
                      'Groups' => array(
                        array(
                          'GroupName' => 'Ubuntu Server 14-04 LTS -HVM--14-04 LTS 20151117-AutogenByAWSMP-',
                          'GroupId' => 'sg-' . bin2hex(openssl_random_pseudo_bytes(4)),
                        ),
                      ),
                      'Attachment' => array(
                        'AttachmentId' => 'eni-attach-' . bin2hex(openssl_random_pseudo_bytes(4)),
                        'DeviceIndex' => 0,
                        'Status' => 'attached',
                        'AttachTime' => array(
                          'date' => '2016-05-24 00:21:39.000000',
                          'timezone_type' => 2,
                          'timezone' => 'Z',
                        ),
                        'DeleteOnTermination' => 1,
                      ),
                      'Association' => array(
                        'PublicIp' => implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))),
                        'PublicDnsName' => 'ec2-' . implode('-', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))) . '.compute-1.amazonaws.com',
                        'IpOwnerId' => 'amazon',
                      ),
                      'PrivateIpAddresses' => array(
                        array(
                          'PrivateIpAddress' => implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))),
                          'PrivateDnsName' => 'ip-' . implode('-', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))) . '.ec2.internal',
                          'Primary' => 1,
                          'Association' => array(
                            'PublicIp' => implode('.', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))),
                            'PublicDnsName' => 'ec2-' . implode('-', array(rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255))) . '.compute-1.amazonaws.com',
                            'IpOwnerId' => 'amazon',
                          ),
                        ),
                      ),
                    ),
                  ),  // 'NetworkInterfaceId',
                  'EbsOptimized' => '',
                ), // Instances[0]
              ), // 'Instances'
            ), // Reservations[0]
          ), // 'Reservations'
        ), // 'DescribeInstances'
*/
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getEc2Client(string $cloud_context = '') {

    $config = \Drupal::config('aws_cloud.settings');
    $cloud_context = Config::load($cloud_context);

    if (empty($cloud_context)
    && !$config->get('aws_cloud_test_mode')) {

      return NULL;
    }

    try {

      if (empty($this->ec2_clients[$cloud_context])) { // @FIXME 2016/06/03 Always NULL since not using $this->ec2_clients
/*
        $mock = new MockHandler(array($this->getMockhandlerResult()));
// or
//  $mock->append($this->getMockhandlerResult());
*/
//      $mock = new MockHandler();
//      $mock = new MockHandler(array($this->getMockhandlerResult()));
    
        $ec2_config =  $config->get('aws_cloud_test_mode') // This is boolean value - Test mode: TRUE or FALSE
        // test mode
        ? [
          'credentials' => [
            'key'    => bin2hex(openssl_random_pseudo_bytes(8)),
            'secret' => bin2hex(openssl_random_pseudo_bytes(10)),
          ],
          'region'   => 'us-east-1',
          'version'  => 'latest',
//        'handler'  => $mock,
        ]
        // Actual Client
        : [
          'credentials' => [
            'key'    => $cloud_context->aws_access_key(),
            'secret' => $cloud_context->aws_secret_key(),
          ],
          'region'   => $cloud_context->region(),
          'version'  => $cloud_context->api_version(),
          'endpoint' => $cloud_context->endpoint(),
        ];

        // You may want to restart Apache2 (sytemctl restart apache2)
        // if you encounter the error like: "Error: Class 'Aws\\Ec2\\Ec2Client' not found in
        // /var/www/html/.../modules/cloud/modules/cloud_service_providers/aws_cloud/src/Controller/Ec2/ApiController.php
        // on line 4xx.
        $ec2_clients = Ec2Client::factory($ec2_config);
      } // until here

      return $ec2_clients;
    }
//  catch (UnresolvedApiException $e) {
    catch (\Exception $e) {
// @FIXME
// Handle exception properly
print "passed: Exception: $e";
// var_dump($e);
exit;
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function execute(string $cloud_context = '', string $operation = '', array $params = []) {

    $result = NULL;

    // Parameter Check
    if(empty($cloud_context)
    || empty($operation)
    || empty($params)) {

      return NULL;
    }

    $ec2_client = $this->getEc2Client($cloud_context);
    if ($ec2_client === NULL) {

      return NULL;
    }

    try {

      $config = \Drupal::config('aws_cloud.settings');
      $cloud_context = Config::load($cloud_context);

      if ($config->get('aws_cloud_test_mode')) {
        $mock = new MockHandler([$this->getMockhandlerResult($params)]);
        $ec2_client->getHandlerList()->setHandler($mock); // unless using 'handler' above.
        $command = $ec2_client->getCommand($operation, $params);
        $result = $ec2_client->execute($command)->search($operation);

      } else {

        $command = $ec2_client->getCommand($operation, $params);
        $result = $ec2_client->execute($command);
      }

      return $result;
    }
    catch (Ec2Exception $e) {

      $status  = 'error';
      $message = $this->t('Error: The operation "@operation" couldn\'t be performed.', [
                   '@operation'  => $operation,
                 ]);
      drupal_set_message($message, $status);

      $message = $this->t('Error Info: @error_info', [
                   '@error_info'  => $e->getAwsErrorCode(),
                 ]);
      drupal_set_message($message, $status);

      $message = $this->t('Error from: @error_type-side', [
                   '@error_type'  => $e->getAwsErrorType(),
                 ]);
      drupal_set_message($message, $status);

      $message = $this->t('Status Code: @status_code', [
                   '@status_code'  => $e->getStatusCode(),
                 ]);
      drupal_set_message($message, $status);

      $message = $this->t('Message: @msg', ['@msg' => $e->getAwsErrorMessage()]);

      drupal_set_message($message, $status);

    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function updateInstanceList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateInstances();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_instance.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateImageList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateImages();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_image.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateSecurityGroupList(ConfigInterface $cloud_context) {
    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateSecurityGroups();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_security_group.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateNetworkInterfaceList(ConfigInterface $cloud_context) {
    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateNetworkInterfaces();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_network_interface.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateElasticIpList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateElasticIp();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_elastic_ip.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateKeyPairList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateKeyPairs();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_key_pair.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateVolumeList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateVolumes();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_volume.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateSnapshotList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateSnapshots();

    if ($updated == FALSE) {
      // @todo setup messenger class and write the error out
    }

    return $this->redirect('entity.aws_cloud_snapshot.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function terminateInstance(Instance $instance) {
    return $this->awsEc2Service->terminateInstance([
      'InstanceIds' => [$instance->instance_id()],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function deregisterImage(Image $image) {
    // @TODO: add support for this function
    $operation = 'DeregisterImage';
    $params = [
      'DryRun'  => $this->is_dryrun,
      'ImageId' => $image->image_id(),
    ];

    try {


      $this->execute($image->cloud_context(), $operation, $params);

      return TRUE;
    }
    catch (Ec2Exception $e) {

    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteSecurityGroup(SecurityGroup $security_group) {
    return $this->awsEc2Service->deleteSecurityGroup([
      'GroupId'   => $security_group->group_id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteNetworkInterface(NetworkInterface $network_interface) {
    return $this->awsEc2Service->deleteNetworkInterface([
      'NetworkInterfaceId' => $network_interface->network_interface_id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteElasticIp(ElasticIp $elastic_ip) {
    return $this->awsEc2Service->releaseAddress([
      'AllocationId' => $elastic_ip->allocation_id(),
    ]);
  }

  /**
   * @inheritdoc}
   */
  public function deleteKeyPair(KeyPair $key_pair) {
    return $this->awsEc2Service->deleteKeyPair([
      'KeyName' => $key_pair->key_pair_name(),
    ]);
  }

  /**
   * @inheritdoc}
   */
  public function deleteVolume(Volume $volume) {
    return $this->awsEc2Service->deleteVolume([
      'VolumeId' => $volume->volume_id(),
    ]);
  }

  /**
   * @inheritdoc}
   */
  public function deleteSnapshot(Snapshot $snapshot) {
    return $this->awsEc2Service->deleteSnapshot([
      'SnapshotId' => $snapshot->snapshot_id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function launchInstance(Instance $instance) {

    $key_name       = preg_replace('/ \([^\)]*\)$/', '', $instance->key_pair_name());
    $security_group = preg_replace('/ \([^\)]*\)$/', '', $instance->security_groups()); // @TODO: To Array

    $operation = 'RunInstances';
    $params = [
      // The following parameters are required.
      'DryRun'         => $this->is_dryrun,
      'ImageId'        => $instance->image_id(), // e.g.ami-8936e0e9 | ubuntu/images/hvm-ssd/ubuntu-xenial-16.04-amd64-server-20160830 (ami-8936e0e9)
      'MaxCount'       => $instance->max_count(),
      'MinCount'       => $instance->min_count(),
      'InstanceType'   => $instance->instance_type(),
      // The following parameters are optional.
      /*
      'NetworkInterfaces' => array(
        array(
          // 'NetworkInterfaceId' => 'string',
          // 'DeviceIndex' => integer,
          // 'SubnetId' => 'string',
          // 'Description' => 'string',
          // 'PrivateIpAddress' => 'string',
          // 'Groups' => array('string', ... ),
          // 'DeleteOnTermination' => true || false,
          'PrivateIpAddresses' => array(
            array(
              // PrivateIpAddress is required
              'PrivateIpAddress' => '10.0.0.100',
              'Primary' => true, // true || false,
            ),
            // ... repeated
          ),
          // 'SecondaryPrivateIpAddressCount' => integer,
          // 'AssociatePublicIpAddress' => true || false,
        ),
        // ... repeated
      ),
      */
      'Monitoring'     => ['Enabled' => $instance->is_monitoring() ? true : false],
      'KeyName'        => $key_name,
      'Placement'      => ['AvailabilityZone' => $instance->availability_zone()],
      'SecurityGroups' => [$security_group], // @TODO: To Array
    ];

    // The following parameters are optional.
    $params['KernelId' ] ?: $instance->kernel_id() ;
    $params['RamdiskId'] ?: $instance->ramdisk_id();
    $params['UserData' ] ?: $instance->user_data() ;

//    $result = [];
//    try {
//
//      $result = $this->execute($instance->cloud_context(), $operation, $params);
//    }
//    catch (Ec2Exception $e) {
//
//    }
    $result = $this->launchUsingParams($instance->cloud_context(), $params);
    return $result;
  }

  public function launchUsingParams($cloud_context, $params = []) {
    if (count($params) == 0) {
      return NULL;
    }
    return $this->awsEc2Service->runInstances($params);
  }

  /**
   * {@inheritdoc}
   */
  public function createImage(Image $image) {

    $operation = 'CreateImage';
    $params = [
      'DryRun'      => $this->is_dryrun,
    // InstanceId is required.
      'InstanceId'  => $image->instance_id(),
    // Name is required.
      'Name'        => $image->label(),
      'Description' => $image->description(),
    ];
    $result = [];

    try {

      $result = $this->execute($image->cloud_context(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function createSecurityGroup(SecurityGroup $security_group) {

    $operation = 'CreateSecurityGroup';
    $params = [
      'DryRun'      => $this->is_dryrun,
      // GroupName is required.
      'GroupName'   => $security_group->group_name(),
      'VpcId'       => $security_group->vpc_id(),
      'Description' => $security_group->description(),
    ];
    $result = [];

    try{

      $result = $this->execute($security_group->cloud_context(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function createNetworkInterface(NetworkInterface $network_interface) {

    $operation = 'CreateNetworkInterface';
    $params = [
      'DryRun'                         => $this->is_dryrun,
    // SubnetId is required.
      'SubnetId'                       => $network_interface->subnet_id(),
    // PrivateIpAddresses is an array and required.
      'PrivateIpAddress'               => $network_interface->primary_private_ip(),
    // Groups is an array.
      'Groups'                         => [$network_interface->security_groups()],
    // PrivateIpAddresses is an array and PrivateIpAddress is required.
      'PrivateIpAddresses'             => [
                                            [
                                              'PrivateIpAddress' => $network_interface->secondary_private_ips(),  // REQUIRED
                                              'Primary'          => $network_interface->primary() ? true : false, // TRUE or FALSE
                                            ],
                                          ],
      'SecondaryPrivateIpAddressCount' => count(explode(',', $network_interface->secondary_private_ips())),
      'Description'                    => $network_interface->description(),
    ];
    $result = [];

    try {

      $result = $this->execute($network_interface->cloud_context(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function createElasticIp(ElasticIp $elastic_ip) {

    $operation = 'AllocateAddress';
    $params = [
      'DryRun' => $this->is_dryrun,
    // Domain is optional (standard | vpc).
      'Domain' => $elastic_ip->domain(),
    ];
    $result = [];

    try {

      $result = $this->execute($elastic_ip->cloud_context(), $operation, $params);
    }
    catch (Exception $e) {

    }

    return $result;
  }

  /**
   * @inheritdoc}
   */
  public function createKeyPair(KeyPair $key_pair) {

    $operation = 'CreateKeyPair';
    $params = [
      'DryRun'  => $this->is_dryrun,
    // KeyName is required.
      'KeyName' => $key_pair->key_pair_name(),
    ];
    $result = [];

    try {

      $result = $this->execute($key_pair->cloud_context(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    return $result;
  }

  /**
   * @inheritdoc}
   */
  public function createVolume(Volume $volume) {

    $operation = 'CreateVolume';
    $params = [
      'DryRun'           => $this->is_dryrun,
      'Size'             => $volume->size(),
      'SnapshotId'       => $volume->snapshot_id(),
      'AvailabilityZone' => $volume->availability_zone(), // REQUIRED.
      'VolumeType'       => $volume->volume_type(),
      'Iops'             => $volume->iops(),
      'Encrypted'        => $volume->encrypted() ? true : false,
      'KmsKeyId'         => $volume->kms_key_id(),
    ];
    $result = [];

    try {

      $result = $this->execute($volume->cloud_context(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    return $result;
  }

  /**
   * @inheritdoc}
   */
  public function createSnapshot(Snapshot $snapshot) {

   $operation = 'CreateSnapshot';
   $params = [
      'DryRun'      => $this->is_dryrun,
    // VolumeId is required.
      'VolumeId'    => $snapshot->volume_id(),
      'Description' => $snapshot->description(),
    ];
    $result = [];

    try {

      $result = $this->execute($snapshot->cloud_context(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    return $result;
  }

  /**
   * @inheritdoc}
   */
  public function getAvailabilityZones(ConfigInterface $cloud_context) {
    return $this->awsEc2Service->getAvailabilityZones();
  }

  /**
   * @inheritdoc}
   */
  public function getVpcs(ConfigInterface $cloud_context) {

   $operation = 'DescribeVpcs';
   $params = [
      'DryRun'      => $this->is_dryrun,
    ];
    $result = [];

    try {

      $result = $this->execute($cloud_context->id(), $operation, $params);
    }
    catch (Ec2Exception $e) {

    }

    $vpcs = [];
    foreach (array_column($result['Vpcs'], 'VpcId') as $key => $vpc)
      $vpcs[$vpc] = $result['Vpcs'][$key]['CidrBlock'] . " ($vpc)";

    return $vpcs;
  }
}
