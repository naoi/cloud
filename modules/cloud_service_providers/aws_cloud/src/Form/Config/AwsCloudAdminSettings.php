<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Form\Config\AwsCloudAdminSettings.
 */

// Created by yas 2016/06/02.
namespace Drupal\aws_cloud\Form\Config;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class AWS Cloud Admin Settings.
 */
class AwsCloudAdminSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'aws_cloud_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['aws_cloud.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('aws_cloud.settings');

    $form['aws_cloud_test_mode'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable test mode?'),
      '#default_value' => $config->get('aws_cloud_test_mode'),
      '#description' => t('This enables you to test the AWS Cloud module settings without accessing AWS.'),
    ];

    $form['aws_cloud_instance_types'] = [
      '#type' => 'textarea',
      '#title' => t('AWS Instance Types'),
      '#default_value' => $config->get('aws_cloud_instance_types'),
      '#description' => t('This is a list of AWS instance types. Enter them one per line'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('aws_cloud.settings');
    $form_state->cleanValues();

    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, Html::escape($value));
    }
    $config->save();
  }
}
