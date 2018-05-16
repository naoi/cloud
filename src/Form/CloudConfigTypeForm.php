<?php

namespace Drupal\cloud\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CloudConfigTypeForm.
 */
class CloudConfigTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $cloud_config_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $cloud_config_type->label(),
      '#description' => $this->t("Label for the Cloud config type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $cloud_config_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\cloud\Entity\CloudConfigType::load',
      ],
      '#disabled' => !$cloud_config_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $cloud_config_type = $this->entity;
    $status = $cloud_config_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Cloud config type.', [
          '%label' => $cloud_config_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Cloud config type.', [
          '%label' => $cloud_config_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($cloud_config_type->toUrl('collection'));
  }

}
