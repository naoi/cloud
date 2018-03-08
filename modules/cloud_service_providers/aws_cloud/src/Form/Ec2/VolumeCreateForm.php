<?php

// Updated by yas 2016/09/11
// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;
use Drupal\aws_cloud\Controller\Ec2\ApiController;
use Drupal\aws_cloud\Entity\Config\Config;

/**
 * Form controller for the Volume entity create form.
 *
 * @ingroup aws_cloud
 */
class VolumeCreateForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {

    $cloudContext = Config::load($cloud_context);
    if(isset($cloudContext)) {

      $cloud_type = $cloudContext->cloud_type();
      $this->apiController = new ApiController($this->query_factory);
    }
    else {

      $status  = 'error';
      $message = $this->t("Not found: AWS Cloud provider '@cloud_context'", [
        '@cloud_context'  => $cloud_context,
      ]);
      drupal_set_message($message, $status);
    }

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

    $availability_zones = $this->apiController->getAvailabilityZones($cloudContext);
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

    $apiController = new ApiController($this->query_factory);
    $result = $apiController->createVolume($entity);

    $status  = 'error';
    $message = $this->t('The @label "%label" couldn\'t create.', [
      '@label' => $entity->getEntityType()->getLabel(),
      '%label' => $entity->label(),
    ]);

    if (isset($result['VolumeId'])
    && ($entity->setVolumeId($result['VolumeId']))
    && ($entity->setCreated($result['CreateTime']))
    && ($entity->setState($result['State']))
    && ($entity->save())) {

      $status  = 'status';
      $message = $this->t('The @label "%label (@volume_id)" has been created.', [
        '@label'     => $entity->getEntityType()->getLabel(),
        '%label'     => $entity->label(),
        '@volume_id' => $entity->volume_id(),
      ]);

      $form_state->setRedirectUrl($entity
                 ->urlInfo('collection')
                 ->setRouteParameter('cloud_context', $entity->cloud_context()));
    }

    drupal_set_message($message, $status);
  }

}
