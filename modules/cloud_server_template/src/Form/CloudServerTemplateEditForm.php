<?php

// Updated by yas 2015/06/08
// updated by yas 2015/06/05
// updated by yas 2015/06/03
// updated by yas 2015/06/01
// updated by yas 2015/05/31
// created by yas 2015/05/30.
namespace Drupal\cloud_server_template\Form;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the CloudServerTemplate entity edit forms.
 *
 * @ingroup cloud_server_template
 */
class CloudServerTemplateEditForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\cloud_server_template\Entity\CloudServerTemplate */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->label(),
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

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

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['instance_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Instance Type'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->instance_type(),
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['image_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Image ID'),
      '#size'          => 60,
      '#default_value' => $entity->image_id(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['kernel_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Kernel ID'),
      '#size'          => 60,
      '#default_value' => $entity->kernel_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['ramdisk_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Ramdisk ID'),
      '#size'          => 60,
      '#default_value' => $entity->kernel_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['group_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Group ID'),
      '#size'          => 60,
      '#default_value' => $entity->group_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['ssh_key_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('SSH Key'),
      '#size'          => 60,
      '#default_value' => $entity->ssh_key_id(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['user_data'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('User Data'),
      '#size'          => 60,
      '#default_value' => $entity->user_data(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['instance_count'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Count'),
      '#size'          => 60,
      '#default_value' => $entity->instance_count(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['langcode'] = [
      '#title' => t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    ];

    $form['actions'] = $this->actions($form, $form_state);

    return $form;
  }

}
