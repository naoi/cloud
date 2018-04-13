<?php

// Updated by yas 2016/09/11
// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\aws_cloud\Entity\Config\Config;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the Volume entity create form.
 *
 * @ingroup aws_cloud
 */
class VolumeCreateForm extends AwsCloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {

    $cloudContext = Config::load($cloud_context);
    if(!isset($cloudContext)) {
      $message = $this->t("Not found: AWS Cloud provider '@cloud_context'", [
        '@cloud_context'  => $cloud_context,
      ]);
      $this->messenger->addError($message);
    }

    $this->awsEc2Service->setCloudContext($cloud_context);

    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\Volume */
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

    $form['snapshot_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Snapshot ID'),
      '#size'          => 60,
      '#default_value' => $entity->snapshot_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
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

    $form['size'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Size (GB)'),
      '#size'          => 60,
      '#default_value' => $entity->size(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['iops'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('IOPS'),
      '#size'          => 60,
      '#default_value' => $entity->iops(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['encrypted'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Encrypted'),
      '#size'          => 60,
      '#default_value' => $entity->encrypted(),
      '#weight'        => -5,
      '#required'      => FALSE,

    ];
    $form['kms_key_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('kms_key_id'),
      '#size'          => 60,
      '#default_value' => $entity->kms_key_id(),
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

    $params = [
      'Size'             => $entity->size(),
      'SnapshotId'       => $entity->snapshot_id(),
      'AvailabilityZone' => $entity->availability_zone(), // REQUIRED.
      'VolumeType'       => $entity->volume_type(),
      // TODO: Need to specify volume type before reenabling this
      //'Iops'             => (int)$entity->iops(),
      //'Encrypted'        => $entity->encrypted() ? true : false,
    ];
    $kms_key_id = $entity->kms_key_id();
    if (isset($kms_key_id)) {
      $params['KmsKeyId'] = $entity->kms_key_id();
    }

    $result = $this->awsEc2Service->createVolume($params);

    if (isset($result['VolumeId'])
    && ($entity->setVolumeId($result['VolumeId']))
    && ($entity->setCreated($result['CreateTime']))
    && ($entity->setState($result['State']))
    && ($entity->save())) {

      $message = $this->t('The @label "%label (@volume_id)" has been created.', [
        '@label'     => $entity->getEntityType()->getLabel(),
        '%label'     => $entity->label(),
        '@volume_id' => $entity->volume_id(),
      ]);
      $this->messenger->addMessage($message);
      $form_state->setRedirect('entity.volume.collection', ['cloud_context' => $entity->cloud_context()]);
    }
    else {
      $message = $this->t('The @label "%label" couldn\'t create.', [
        '@label' => $entity->getEntityType()->getLabel(),
        '%label' => $entity->label(),
      ]);
      $this->messenger->addError($message);
    }

  }

}
