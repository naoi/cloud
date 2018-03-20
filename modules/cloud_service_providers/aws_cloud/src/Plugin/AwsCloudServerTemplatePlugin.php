<?php

namespace Drupal\aws_cloud\Plugin;

use Aws\Ec2\Exception\Ec2Exception;
use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;
use Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\aws_cloud\Controller\Ec2\ApiController;

class AwsCloudServerTemplatePlugin extends PluginBase implements CloudServerTemplatePluginInterface {

  /**
   * Returns the entity bundle
   * @return string
   */
  public function getEntityBundleName() {
    return $this->pluginDefinition['entity_bundle'];
  }

  public function launch(CloudServerTemplateInterface $cloud_server_template) {

    $apiController = new ApiController(\Drupal::service('entity.query'));
    // run execute on RunInstances

    // fields that are required by API
    /**
     * MaxCount
     * MinCount
     * Need Monitoring field
     *
     * t2 needs VPC. which isn't working.  Need to implement the following url
     *  https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/t2-instances.html
     * t2 instances need an ebs backed store
     *  - Need to add network interfaceID for t2
     * m1.small works
     */
    $params = [];
    $params['DryRun'] = true; //dry run is turned on.
    $params['ImageId'] = $cloud_server_template->get('field_image_id')->entity->get('image_id')->value;
    $params['MaxCount'] = $cloud_server_template->get('field_instance_count')->value;
    $params['MinCount'] = $cloud_server_template->get('field_instance_count')->value;
    $params['Monitoring']['Enabled'] = false;  //TODO: hard-coded, need to move to aws_cloud bundle
    $params['InstanceType'] = $cloud_server_template->get('field_instance_type')->value;
    $params['KeyName'] = $cloud_server_template->get('field_ssh_key')->entity->get('key_pair_name')->value;

    // setup optional parameters
    $params['KernelId' ] ?: $cloud_server_template->get('field_kernel_id')->value;
    $params['RamdiskId'] ?: $cloud_server_template->get('field_ram')->value;
    $params['UserData' ] ?: $cloud_server_template->get('field_user_data')->value ;
    $params['Placement']['AvailabilityZone'] ?: $cloud_server_template->get('field_availability_zone')->value;

    // look the security groups - This needs to be validated upon server template entry.
    if (strpos($params['InstanceType'], 't2') !== FALSE) {
      $security_groups = [];
      foreach ($cloud_server_template->get('field_security_group') as $ref) {
        $security_groups[] = $ref->entity->get('group_name')->value;
      }

      if (count($security_groups)) {
        $params['SecurityGroup'] = $security_groups;
      }
    }
    else {
      // T2 support which entails network ids and such
    }

//    $params['SecurityGroupId']['sg-a9aa31d0'];

    $result = [];
    $result = $apiController->execute($cloud_server_template->cloud_context(), 'RunInstances', $params);
    // if errors, NULL is returned.  support that
  }

}
