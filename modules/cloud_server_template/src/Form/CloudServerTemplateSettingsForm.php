<?php

// Created by yas 2015/05/30.
namespace Drupal\cloud_server_template\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class CloudServerTemplateSettingsForm.
 *
 * @package Drupal\cloud_server_template\Form
 *
 * @ingroup cloud_server_template
 */
class CloudServerTemplateSettingsForm extends CloudContentForm {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'CloudServerTemplate_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * Define the form used for CloudServerTemplate  settings.
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
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    $form['CloudServerTemplate_settings']['#markup'] = 'Settings form for CloudServerTemplate. Manage field settings here.';

    $form['actions'] = $this->actions($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);
    foreach ($operations as $key => $operation) {
      $operations[$key]['url']
        ->setRouteParameter('cloud_context', $entity->getCloudContext());
    }
    return $operations;
  }

}
