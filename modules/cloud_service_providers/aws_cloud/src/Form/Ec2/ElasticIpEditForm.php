<?php

// Updated by yas 2016/06/03
// Created by yas 2016/05/30.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the ElasticIp entity edit forms.
 *
 * @ingroup aws_cloud
 */
class ElasticIpEditForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\ElasticIp */
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
//    '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['public_ip'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Elastic IP'),
      '#maxlength'     => 15,
      '#size'          => 60,
      '#default_value' => $entity->public_ip(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['domain'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Domain (standard | vpc)'),
      '#maxlength'     => 64,
      '#size'          => 60,
      '#default_value' => $entity->domain(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['allocation_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Allocation ID'),
      '#maxlength'     => 64,
      '#size'          => 60,
      '#default_value' => $entity->allocation_id(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['instance_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Instance ID'),
      '#maxlength'     => 64,
      '#size'          => 60,
      '#default_value' => $entity->instance_id(),
      '#required'      => FALSE,
      '#weight'        => -5,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

}
