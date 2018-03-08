<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Form\Ec2\InstanceDeleteForm.
 */

// Updated by yas 2016/09/07
// Updated by yas 2016/09/06
// Updated by yas 2016/06/12
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// updated by yas 2016/05/29
// updated by yas 2016/05/28
// updated by yas 2016/05/25
// updated by yas 2016/05/21
// created by yas 2016/05/19

namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentDeleteForm;
use Drupal\aws_cloud\Entity\Config\Config;
use Drupal\aws_cloud\Controller\Ec2\ApiController;

/**
 * Provides a form for deleting a Instance entity.
 *
 * @ingroup aws_cloud
 */
class InstanceDeleteForm extends CloudContentDeleteForm {

  // delegate parent class - CloudContentDeleteFrom

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {

    return t('Delete | Terminate');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $entity = $this->entity;
    $apiController = new ApiController($this->query_factory);

    $status  = 'error';
    $message = $this->t('The @type "@label" couldn\'t terminate.', [
                  '@type'  => $entity->getEntityType()->getLabel(),
                  '@label' => $entity->label(),
               ]);

    $result = $apiController->terminateInstance($entity);
    if(isset($result['TerminatingInstances'][0]['InstanceId'])) {

      $status  = 'status';
      $message = $this->t('The @type "@label" has been terminated.', [
                           '@type'  => $entity->getEntityType()->getLabel(),
                           '@label' => $entity->label(),
                        ]);

      $entity->delete();
    }

    drupal_set_message($message, $status);

    $apiController->updateInstanceList(Config::load($entity->cloud_context()));
    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
