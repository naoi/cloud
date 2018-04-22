<?php

namespace Drupal\aws_cloud\Plugin;

use Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;
use Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\Messenger;

class AwsCloudServerTemplatePlugin extends PluginBase implements CloudServerTemplatePluginInterface, ContainerFactoryPluginInterface {


  /**
   * @var \Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
   */
  protected $awsEc2Service;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * AwsCloudServerTemplatePlugin constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\aws_cloud\Service\AwsEc2ServiceInterface $aws_ec2_service
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AwsEc2ServiceInterface $aws_ec2_service, Messenger $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->awsEc2Service = $aws_ec2_service;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('aws_cloud.ec2'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityBundleName() {
    return $this->pluginDefinition['entity_bundle'];
  }

  /**
   * {@inheritdoc}
   */
  public function launch(CloudServerTemplateInterface $cloud_server_template) {

    $params = [];
    $params['DryRun'] = $cloud_server_template->get('field_test_only')->value == "0" ? FALSE: TRUE;
    $params['ImageId'] = $cloud_server_template->get('field_image_id')->entity->get('image_id')->value;
    $params['MaxCount'] = $cloud_server_template->get('field_max_count')->value;
    $params['MinCount'] = $cloud_server_template->get('field_min_count')->value;
    $params['Monitoring']['Enabled'] = $cloud_server_template->get('field_monitoring')->value == "0" ? FALSE: TRUE;
    $params['InstanceType'] = $cloud_server_template->get('field_instance_type')->value;
    if (isset($cloud_server_template->get('field_ssh_key')->entity)) {
      $params['KeyName'] = $cloud_server_template->get('field_ssh_key')->entity->get('key_pair_name')->value;
    }

    if ($cloud_server_template->get('field_image_id')->entity->get('root_device_type') == 'ebs') {
      $params['InstanceInitiatedShutdownBehavior'] = $cloud_server_template->get('field_instance_shutdown_behavior')->value;
    }

    // setup optional parameters
    if (isset($cloud_server_template->get('field_kernel_id')->value)) {
      $params['KernelId'] = $cloud_server_template->get('field_kernel_id')->value;
    }
    if (isset($cloud_server_template->get('field_ram')->value)) {
      $params['RamdiskId'] = $cloud_server_template->get('field_ram')->value;
    }
    if (isset($cloud_server_template->get('field_user_data')->value)) {
      $params['UserData'] = $cloud_server_template->get('field_user_data')->value;
    }
    if (isset($cloud_server_template->get('field_availability_zone')->value)) {
      $params['Placement']['AvailabilityZone'] = $cloud_server_template->get('field_availability_zone')->value;
    }

    $params['SecurityGroup'] = [];
    foreach ($cloud_server_template->get('field_security_group') as $group) {
      if (isset($group->entity)) {
        $params['SecurityGroup'][] = $group->entity->get('group_name')->value;
      }
    }

    // set the subnet id - This is required for t2.* instances
    if (isset($cloud_server_template->get('field_network')->entity)) {
      $params['SubnetId'] = $cloud_server_template->get('field_network')->entity->subnet_id();
    }

    // Let other modules alter the parameters before sending to AWS
    //$params = \Drupal::moduleHandler()->invokeAll('aws_cloud_parameter_alter', [$params, $cloud_server_template->cloud_context()]);

    $this->awsEc2Service->setCloudContext($cloud_server_template->cloud_context());

    if ($this->awsEc2Service->runInstances($params) != null) {
      // update instances after launch
      $this->awsEc2Service->updateInstances();
      $this->messenger->addStatus('Instance launched.');
      $return_route = [
        'route_name' => 'entity.aws_cloud_instance.collection',
        'params' => ['cloud_context' => $cloud_server_template->cloud_context()],
      ];
    }
    else {
      $return_route = [
        'route_name' => 'entity.cloud_server_template.canonical',
        'params' => ['cloud_server_template' => $cloud_server_template->id()],
      ];
    }

    return $return_route;
  }

}
