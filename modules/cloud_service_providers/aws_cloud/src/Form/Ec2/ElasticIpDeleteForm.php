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

/**
 * Provides a form for deleting a ElasticIp entity.
 *
 * @ingroup aws_cloud
 */
class ElasticIpDeleteForm extends AwsDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    /* @var \Drupal\aws_cloud\Entity\Ec2\ElasticIp $entity */
    $entity = $this->entity;
    $this->awsEc2Service->setCloudContext($entity->cloud_context());

    $allocation_id = $entity->allocation_id();
    $public_ip = $entity->public_ip();
    if ($this->awsEc2Service->releaseAddress([
        'AllocationId' => isset($allocation_id) ? $entity->allocation_id() : '',
        'PublicIp' => isset($public_ip) ? $entity->public_ip() : '',
      ]) != NULL
    ) {

      $message = $this->t('The @type "@label" has been deleted.', [
        '@type' => $entity->getEntityType()->getLabel(),
        '@label' => $entity->label(),
      ]);

      $entity->delete();
      $this->messenger->addMessage($message);
    }
    else {
      $message = $this->t('The @type "@label" couldn\'t delete.', [
        '@type' => $entity->getEntityType()->getLabel(),
        '@label' => $entity->label(),
      ]);
      $this->messenger->addError($message);
    }

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
