<?php

// Created by yas 2016/05/30.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the CloudScripting entity edit forms.
 *
 * @ingroup aws_cloud
 */
class ImageEditForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\Image */
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
      '#disabled'      => TRUE,
    ];

    $form['name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->name(),
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['image_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Image ID'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->image_id(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['status'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Status'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->status(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['architecture'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Architecture'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->architecture(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['virtualization_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Virtualization Type'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->virtualization_type(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['root_device_name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Root Device Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->root_device_name(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['ramdisk_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Ramdisk ID'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->ramdisk_id(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['product_code'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Product Code'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->product_code(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['ami_name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('AMI Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->ami_name(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['source'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Source'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->source(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['state_reason'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('State Reason'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->state_reason(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['platform'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('platform'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->platform(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['image_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Image Type'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->image_type(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['root_device_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Root Device Type'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->root_device_type(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['kernel_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Kernel ID'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->kernel_id(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['block_devices'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Block Devices'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->block_devices(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['visibility'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Visibility'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->visibility(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['owner'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Owner'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->owner(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['description'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Description'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->description(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['langcode'] = [
      '#title'         => t('Language'),
      '#type'          => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages'     => Language::STATE_ALL,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

}
