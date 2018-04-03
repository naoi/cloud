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

/**
 * Provides a form for deleting a Instance entity.
 *
 * @ingroup aws_cloud
 */
class InstanceDeleteForm extends AwsDeleteForm {

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
    $this->awsEc2Service->setCloudContext($entity->cloud_context());

    $result = $this->awsEc2Service->terminateInstance([
      'InstanceIds' => [$entity->instance_id()]
    ]);

    if(isset($result['TerminatingInstances'][0]['InstanceId'])) {

      $message = $this->t('The @type "@label" has been terminated.', [
                           '@type'  => $entity->getEntityType()->getLabel(),
                           '@label' => $entity->label(),
                        ]);

      $entity->delete();
      $this->messenger->addMessage($message);
    }
    else {
      $message = $this->t('The @type "@label" couldn\'t terminate.', [
        '@type'  => $entity->getEntityType()->getLabel(),
        '@label' => $entity->label(),
      ]);
      $this->messenger->addError($message);
    }

    $this->awsEc2Service->updateInstances();
    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
