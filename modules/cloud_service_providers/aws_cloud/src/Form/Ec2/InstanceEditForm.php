<?php

// Updated by yas 2016/05/31.
// Created by yas 2016/05/30.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the CloudScripting entity edit forms.
 *
 * @ingroup aws_cloud
 */
class InstanceEditForm extends AwsCloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
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
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,

    ];

    $form['name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->label(),
      '#required'      => FALSE,
      '#weight'        => -5,
    ];

    $form['image_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('EC2 Image'),
      '#size'          => 60,
      '#default_value' => $entity->image_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['key_pair_name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Key Pair Name'),
      '#size'          => 60,
      '#default_value' => $entity->key_pair_name(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['is_monitoring'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Monitoring Enabled'),
      '#size'          => 60,
      '#default_value' => $entity->is_monitoring(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['availability_zone'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Availability Zone'),
      '#size'          => 60,
      '#default_value' => $entity->availability_zone(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['security_groups'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Security Groups'),
      '#size'          => 60,
      '#default_value' => $entity->security_groups(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['instance_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Instance Type'),
      '#size'          => 60,
      '#default_value' => $entity->instance_type(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['kernel_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Kernel Image'),
      '#size'          => 60,
      '#default_value' => $entity->kernel_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['ramdisk_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Ramdisk Image'),
      '#size'          => 60,
      '#default_value' => $entity->ramdisk_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['user_data'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('User Data'),
      '#maxlength'     => 4096,
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->user_data(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
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
      '#title'         => t('Language'),
      '#type'          => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages'     => Language::STATE_ALL,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => FALSE,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

  /**
   * {@inheritdocs}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);

    // update the instance name - it is a tag
    $params = [
      'Resources' => [$this->entity->get('instance_id')->value],
      'Tags' => [
        [
          'Key' => 'cloud_instance_nickname',
          'Value' => $this->entity->get('name')->value
        ],
      ]
    ];
    $this->awsEc2Service->setCloudContext($this->entity->get('cloud_context')->value);
    $this->awsEc2Service->createTags($params);
  }

}
