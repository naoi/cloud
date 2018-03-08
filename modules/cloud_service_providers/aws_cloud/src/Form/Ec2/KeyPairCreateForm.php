<?php

// Updated by yas 2016/06/03
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;
use Drupal\aws_cloud\Controller\Ec2\ApiController;

/**
 * Form controller for the KeyPair entity create form.
 *
 * @ingroup aws_cloud
 */
class KeyPairCreateForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\KeyPair */
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
      '#required'      => TRUE,
      '#weight'        => -5,
      '#disabled'      => TRUE,
    ];

    $form['key_pair_name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Key Pair Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->key_pair_name(),
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['langcode'] = [
      '#title' => t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $apiController = new ApiController($this->query_factory);
    $result = $apiController->createKeyPair($entity);

    $status  = 'error';
    $message = $this->t('The @label "@key_pair_name" couldn\'t create.', [
      '@label'         => $entity->getEntityType()->getLabel(),
      '@key_pair_name' => $entity->key_pair_name(),
    ]);

    if (isset($result['KeyName'])
    && ($entity->setKeyFingerprint($result['KeyFingerprint']))
    && ($entity->setKeyMaterial($result['KeyMaterial']))
    && ($entity->save())) {

      $status  = 'status';
      $message = $this->t('The @label "@key_pair_name" has been created.', [
        '@label'         => $entity->getEntityType()->getLabel(),
        '@key_pair_name' => $entity->key_pair_name(),
      ]);

      $form_state->setRedirectUrl($entity
                 ->urlInfo('collection')
                 ->setRouteParameter('cloud_context', $entity->cloud_context()));
    }

    drupal_set_message($message, $status);
  }

}
