<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Form\Ec2\SecurityGroupDeleteForm.
 */

// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/21
// Created by yas 2016/05/19

namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentDeleteForm;
use Drupal\aws_cloud\Controller\Ec2\ApiController;

/**
 * Provides a form for deleting a SecurityGroup entity.
 *
 * @ingroup aws_cloud
 */
class SecurityGroupDeleteForm extends CloudContentDeleteForm {

  // delegate parent class - CloudContentDeleteFrom

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $entity = $this->entity;
    $apiController = new ApiController($this->query_factory);

    $status  = 'error';
    $message = $this->t('The @type "@group_name" couldn\'t delete.', [
                  '@type'       => $entity->getEntityType()->getLabel(),
                  '@group_name' => $entity->group_name(),
               ]);

    if($apiController->deleteSecurityGroup($entity)) {

      $status  = 'status';
      $message = $this->t('The @type "@group_name" has been deleted.', [
                    '@type'       => $entity->getEntityType()->getLabel(),
                    '@group_name' => $entity->group_name(),
                 ]);

      $entity->delete();
    }

    drupal_set_message($message, $status);

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
