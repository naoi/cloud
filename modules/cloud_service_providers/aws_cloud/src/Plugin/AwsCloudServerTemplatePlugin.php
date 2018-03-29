<?php

namespace Drupal\aws_cloud\Plugin;

use Drupal\aws_cloud\Controller\Ec2\ApiController;
use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;
use Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AwsCloudServerTemplatePlugin extends PluginBase implements CloudServerTemplatePluginInterface, ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\aws_cloud\Controller\Ec2\ApiController;
   */
  protected $apiController;

  /**
   * Constructs a Drupal\aws_cloud\Plugin\AwsCloudServerTemplatePlugin object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, QueryFactory $entity_query) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // setup the ApiController class
    $this->apiController = new ApiController($entity_query);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.query')
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
    $params['KeyName'] = $cloud_server_template->get('field_ssh_key')->entity->get('key_pair_name')->value;

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
      $params['SecurityGroup'][] = $group->entity->get('group_name')->value;
    }

    // set the subnet id - This is required for t2.* instances
    if (isset($cloud_server_template->get('field_network')->entity)) {
      $params['SubnetId'] = $cloud_server_template->get('field_network')->entity->subnet_id();
    }

    // Let other modules alter the parameters before sending to AWS
    $params = \Drupal::moduleHandler()->invokeAll('aws_cloud_parameter_alter', [$params, $cloud_server_template->cloud_context()]);

    if ($this->apiController->launchUsingParams($cloud_server_template->cloud_context(), $params) != null) {
      drupal_set_message('Instance launched. Please update list to see your new instance(s).');
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
