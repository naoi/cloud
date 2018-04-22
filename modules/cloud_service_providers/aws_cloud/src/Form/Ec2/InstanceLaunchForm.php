<?php
// Updated by yas 2016/09/07
// Updated by yas 2016/09/06
// Updated by yas 2016/09/04
// Updated by yas 2016/06/20
// Updated by yas 2016/06/03
// Updated by yas 2016/06/01
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// Updated by yas 2016/05/26
// Updated by yas 2016/05/25
// Udpated by yas 2016/05/21
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\aws_cloud\Entity\Config\Config;
use Drupal\aws_cloud\Form\Ec2\Instance;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the Instance entity launch form.
 *
 * @ingroup aws_cloud
 */
class InstanceLaunchForm extends AwsCloudContentForm {


  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   *
   * @param array $form
   * @param FormStateInterface $form_state
   * @param string $cloud_context
   *  A cloud_context string value from URL "path".
   * @return array form
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {

    $cloudContext = Config::load($cloud_context);

    if(isset($cloudContext)) {
      $cloud_type = $cloudContext->cloud_type();
      $this->awsEc2Service->setCloudContext($cloudContext->cloud_context());
    }
    else {
      $this->messenger->addError($this->t("Not found: AWS Cloud provider '@cloud_context'", [
        '@cloud_context'  => $cloud_context,
      ]));
    }

    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\Instance */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['cloud_context'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cloud ID'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => !$entity->isNew()
      ? $entity->cloud_context()
      : $cloud_context,
      '#required'      => TRUE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,

    ];

    $form['cloud_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cloud Type'),
      '#size'          => 60,
      '#default_value' => $cloud_type, // @TODO
      '#weight'        => -5,
      '#required'      => TRUE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->label(),
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['image_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('EC2 Image'),
      '#size'          => 60,
      '#default_value' => $entity->image_id(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['min_count'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Min Count'),
      '#maxlength'     => 3,
      '#size'          => 60,
      '#default_value' => 1,
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['max_count'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Max Count'),
      '#maxlength'     => 3,
      '#size'          => 60,
      '#default_value' => 1,
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['key_pair_name'] = [
      '#type'          => 'entity_autocomplete',
      '#target_type'   => 'aws_cloud_key_pair',
      '#title'         => $this->t('Key Pair Name'),
      '#size'          => 60,
      '#default_value' => $entity->key_pair_name(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['is_monitoring'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Monitoring Enabled'),
      '#options'       => [0 => t('No'), 1 => t('Yes')],
      '#default_value' => 0,
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $availability_zones = $this->awsEc2Service->getAvailabilityZones();
    $form['availability_zone'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Availability Zone'),
      '#options'       => $availability_zones,
      // Pick up the first availability zone in the array
      '#default_value' => array_shift($availability_zones),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['security_groups'] = [
      '#type'          => 'entity_autocomplete',
      '#target_type'   => 'aws_cloud_security_group',
      '#title'         => $this->t('Security Groups'),
      '#size'          => 60,
      '#default_value' => $entity->security_groups(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['instance_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Instance Type'),
      '#size'          => 60,
      '#default_value' => $entity->instance_type(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['kernel_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Kernel Image'),
      '#size'          => 60,
      '#default_value' => $entity->kernel_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['ramdisk_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Ramdisk Image'),
      '#size'          => 60,
      '#default_value' => $entity->ramdisk_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['user_data'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('User Data'),
      '#size'          => 60,
      '#default_value' => $entity->user_data(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['login_username'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Login Username'),
      '#size'          => 60,
      '#default_value' => $entity->login_username() ?: 'ec2-user',
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['langcode'] = [
      '#title' => t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;
    $entity->setParams($form);
    $result = $this->launchInstance($entity);

    if (isset($result['Instances'][0]['InstanceId'])
    && ($entity->setInstanceId($result['Instances'][0]['InstanceId']))
    && ($entity->setPublicIp($result['Instances'][0]['PublicIpAddress']))
    && ($entity->setKeyPairName($result['Instances'][0]['KeyName']))
    && ($entity->setInstanceState($result['Instances'][0]['State']['Name']))
    && ($entity->setCreated($result['Instances'][0]['LaunchTime']))
    && ($entity->save())) {

      $instance_ids = [];
      foreach ($result['Instances'] as $instance) {
        $instance_ids[] = $instance['InstanceId'];
      }

      $message = $this->t('The @type "@label (@instance_id)" request has been initiated. '
                           . 'This may take some time. Use Refresh to update the status.', [
                             '@type'        => $entity->getEntityType()->getLabel(),
                             '@label'       => $entity->label(),
                             '@instance_id' => implode(', ', $instance_ids),
                           ]);

      $this->messenger->addMessage($message);
      $form_state->setRedirectUrl($entity
                 ->urlInfo('collection')
                 ->setRouteParameter('cloud_context', $entity->cloud_context()));
    }
    else {
      $message = $this->t('The @type "@label" failed to launch.', [
        '@type'  => $entity->getEntityType()->getLabel(),
        '@label' => $entity->label(),
      ]);

      $this->messenger->addError($message);
    }

  }

  /**
   * Helper method to launch instance
   * @param \Drupal\aws_cloud\Form\Ec2\Instance $instance
   * @return array Results array
   */
  private function launchInstance(Instance $instance) {
    $key_name       = preg_replace('/ \([^\)]*\)$/', '', $instance->key_pair_name());
    $security_group = preg_replace('/ \([^\)]*\)$/', '', $instance->security_groups()); // @TODO: To Array

    $params = [
      // The following parameters are required.
      'ImageId'        => $instance->image_id(), // e.g.ami-8936e0e9 | ubuntu/images/hvm-ssd/ubuntu-xenial-16.04-amd64-server-20160830 (ami-8936e0e9)
      'MaxCount'       => $instance->max_count(),
      'MinCount'       => $instance->min_count(),
      'InstanceType'   => $instance->instance_type(),
      'Monitoring'     => ['Enabled' => $instance->is_monitoring() ? true : false],
      'KeyName'        => $key_name,
      'Placement'      => ['AvailabilityZone' => $instance->availability_zone()],
      'SecurityGroups' => [$security_group], // @TODO: To Array
    ];

    // The following parameters are optional.
    $params['KernelId' ] ?: $instance->kernel_id() ;
    $params['RamdiskId'] ?: $instance->ramdisk_id();
    $params['UserData' ] ?: $instance->user_data() ;
    return $this->awsEc2Service->runInstances($params);
  }
}
