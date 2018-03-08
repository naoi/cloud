<?php

// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
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
class SnapshotEditForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\Snapshot */
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
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['volume_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Volume ID'),
      '#size'          => 60,
      '#default_value' => $entity->volume_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#maxlength'     => 255,
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['encrypted'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Encrypted'),
      '#default_value' => FALSE,
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['langcode'] = [
      '#title'         => t('Language'),
      '#type'          => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages'     => Language::STATE_ALL,
      '#attributes'    => ['readonly' => 'readonly'],
      '#disabled'      => TRUE,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

}
