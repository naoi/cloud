<?php

/**
 * @file
 * Contains \Drupal\aws_cloud\Form\Ec2\KeyPairDeleteForm.
 */

// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/21
// Created by yas 2016/05/19

namespace Drupal\aws_cloud\Form\Ec2;

/**
 * Provides a form for deleting a KeyPair entity.
 *
 * @ingroup aws_cloud
 */
class KeyPairDeleteForm extends AwsDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $entity = $this->entity;
    $this->awsEc2Service->setCloudContext($entity->cloud_context());

    if($this->awsEc2Service->deleteKeyPair([
        'KeyName' => $entity->key_pair_name(),
    ]) != NULL) {

      $message = $this->t('The @type "@label" has been deleted.', [
                 '@type'  => $entity->getEntityType()->getLabel(),
                 '@label' => $entity->label(),
               ]);

      $entity->delete();
      $this->messenger->addMessage($message);
    }
    else {
      $message = $this->t('The @type "@label" couldn\'t delete.', [
        '@type'  => $entity->getEntityType()->getLabel(),
        '@label' => $entity->label(),
      ]);
      $this->messenger->addError($message);
    }

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
