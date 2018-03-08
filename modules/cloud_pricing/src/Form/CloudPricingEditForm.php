<?php

// Changed by yas 2015/06/14
// changed by yas 2015/06/09.
namespace Drupal\cloud_pricing\Form;

use Drupal\cloud\Form\CloudConfigForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CloudPricingEditForm.
 *
 * @package Drupal\cloud_pricing\Form
 */
class CloudPricingEditForm extends CloudConfigForm {

  /**
   * Override buildForm()
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param $cloud_context
   *   A unique machine name for the cloud provider
   *
   * @return array
   *   Form definition array.
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);
    $entity = $this->entity;

    // Get a parameter from the path.
    $cloud_context = \Drupal::routeMatch()->getParameter('cloud_context');

    $form['cloud_context'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cloud ID'),
      '#maxlength'     => 255,
      '#default_value' => $entity->isNew()
    // Get a parameter from the path _or_.
      ? $cloud_context
    // Get default value.
      : $entity->cloud_context(),
      '#required'      => TRUE,
      '#disabled'      => TRUE,
    ];

    $form['instance_type'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Instance Type'),
      '#type'          => 'textfield',
      '#maxlength'     => 255,
      '#default_value' => $entity->instance_type(),
      '#required'      => TRUE,
      '#disabled'      => !$entity->isNew(),
    ];

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#required'      => TRUE,
    ];

    $form['linux_usage'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Linux Usage Charge per Hour ($)'),
      '#maxlength'     => 20,
      '#default_value' => $entity->linux_usage(),
      '#required'      => TRUE,
    ];

    $form['windows_usage'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Windows Usage Charge per Hour ($)'),
      '#maxlength'     => 20,
      '#default_value' => $entity->windows_usage(),
      '#required'      => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $cloud_context = $entity->cloud_context();

    // Check if ID exists.
    if (($entity->isNew() && $this->exist($cloud_context))) {
      $form_state->setError($form, $this->t('The %instance_type already exists.', [
        '%instance_type' => $entity->instance_type(),
      ]));
    }
    if (!preg_match(CLOUD_PRICING_VALID_NUMBER, trim($entity->linux_usage()))) {
      $form_state->setError($form, $this->t('Please enter valid usage for Linux'));
    }
    if (!preg_match(CLOUD_PRICING_VALID_NUMBER, trim($entity->windows_usage()))) {
      $form_state->setError($form, $this->t('Please enter valid usage for Windows'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $cloud_context = $entity->isNew()
                   ? $form['cloud_context']['#value']
                   : $entity->cloud_context();
    $instance_type = $entity->instance_type();

    // When performing action - add.
    if ($entity->isNew()) {

      $id = "$cloud_context.$instance_type";
      $entity->set('id', $id);
      $entity->set('created', time());
    }

    // When performing action - add or edit.
    $entity->set('changed', time());

    /* @FIXME:
    // User Activity Log
    cloud_audit_user_activity(
    array(
    'type' => 'user_activity',
    'message' => t('New Pricing has been added: @pricing',
    array('@pricing' => $form_values['instance_type_select'])),
    'link' => '',
    )
    );
     */
    // TRUE or FALSE
    if ($entity->save()) {
      drupal_set_message(
        $this->t('%instance_type pricing information has been saved.', [
          '%instance_type' => $instance_type,
        ]));
    }
    else {
      // @FIXME:
      // $redirect = 'edit-form';
      $form_state->setError(
        $form, $this->t('The %instance_type pricing information was not saved.', [
          '%instance_type' => $instance_type,
        ]));
    }

    $form_state
      ->setRedirectUrl($entity
      ->urlInfo('collection')
      ->setRouteParameter('cloud_context', $entity->cloud_context()));
  }

}
