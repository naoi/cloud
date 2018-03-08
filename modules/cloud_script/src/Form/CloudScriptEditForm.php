<?php

// Updated by yas 2015/06/08
// updated by yas 2015/06/05
// created by yas 2015/06/03.
namespace Drupal\cloud_script\Form;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the CloudScript entity edit forms.
 *
 * @ingroup cloud_script
 */
class CloudScriptEditForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\cloud_script\Entity\CloudScript */
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
    /*
    $form['cloud_context'] = array(
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
    );
     */
    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Type'),
      '#size'          => 60,
      '#default_value' => $entity->type(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['input_parameters'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Input Prameters'),
      '#size'          => 60,
      '#default_value' => $entity->input_parameters(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['script'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Script'),
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

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

}
