<?php

namespace Drupal\aws_cloud\Plugin;

use Drupal\aws_cloud\Controller\Ec2\ApiController;
use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;
use Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginInterface;
use Drupal\Component\Plugin\PluginBase;

class AwsCloudServerTemplatePlugin extends PluginBase implements CloudServerTemplatePluginInterface {

  /**
   * @var \Drupal\aws_cloud\Controller\Ec2\ApiController;
   */
  protected $apiController;

  /**
   * Set to TRUE to use the DryRun api parameter.
   * @var bool
   */
  private $dryRun = TRUE;

  /**
   * Constructs a Drupal\aws_cloud\Plugin\AwsCloudServerTemplatePlugin object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // setup the ApiController class
    $this->apiController = new ApiController(\Drupal::service('entity.query'));
  }

  /**
   * Returns the entity bundle
   * @return string
   */
  public function getEntityBundleName() {
    return $this->pluginDefinition['entity_bundle'];
  }

  /**
   * Method is responsible for launching an instance in AWS given the cloud
   * server template parameters.
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

    // TODO: check for results and inform the user.
    if ($this->apiController->launchUsingParams($cloud_server_template->cloud_context(), $params) != null) {
     drupal_set_message('Launched!');
    }

  }

}
