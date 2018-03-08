<?php

// Updated by yas 2016/05/25
// udpated by yas 2016/05/20
// created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class KeyPairSettingsForm.
 *
 * @package Drupal\aws_cloud\Ec2\KeyPair\Form
 *
 * @ingroup aws_cloud
 */
class KeyPairSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'aws_cloud_key_pair_settings';
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
   * Define the form used for CloudScripting  settings.
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
    $form['key_pair_settings']['#markup'] = 'Settings form for KeyPair. Manage field settings here.';

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

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
