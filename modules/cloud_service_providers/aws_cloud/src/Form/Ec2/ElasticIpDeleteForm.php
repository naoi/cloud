<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Form\Ec2\ElasticIpDeleteForm.
 */

// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// Updated by yas 2016/05/25
// Updated by yas 2016/05/21
// Created by yas 2016/05/19

namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentDeleteForm;
use Drupal\aws_cloud\Controller\Ec2\ApiController;

/**
 * Provides a form for deleting a ElasticIp entity.
 *
 * @ingroup aws_cloud
 */
class ElasticIpDeleteForm extends CloudContentDeleteForm {

  // delegate parent class - CloudContentDeleteFrom

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $entity = $this->entity;
    $apiController = new ApiController($this->query_factory);

    $status  = 'error';
    $message = $this->t('The @type "@label" couldn\'t delete.', [
                  '@type'  => $entity->getEntityType()->getLabel(),
                  '@label' => $entity->label(),
               ]);
   if($apiController->deleteElasticIp($entity)) {

      $status  = 'status';
      $message = $this->t('The @type "@label" has been deleted.', [
                   '@type'  => $entity->getEntityType()->getLabel(),
                   '@label' => $entity->label(),
                 ]);

      $entity->delete();
    }

    drupal_set_message($message, $status);

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
