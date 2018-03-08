<?php

// Updated by yas 2016/05/24.
namespace Drupal\cloud_alert\Form;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the CloudAlert entity edit forms.
 *
 * @ingroup cloud_alert
 */
class CloudAlertEditForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /* @var $entity \Drupal\cloud_alert\Entity\CloudAlert */
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

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['langcode'] = [
      '#title'         => t('Language'),
      '#type'          => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages'     => Language::STATE_ALL,
    ];

    $form['actions'] = $this->actions($form, $form_state);

    return $form;
  }

}
