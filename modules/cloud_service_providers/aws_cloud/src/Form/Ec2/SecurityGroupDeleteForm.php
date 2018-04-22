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

/**
 * Provides a form for deleting a SecurityGroup entity.
 *
 * @ingroup aws_cloud
 */
class SecurityGroupDeleteForm extends AwsDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $entity = $this->entity;
    $this->awsEc2Service->setCloudContext($entity->cloud_context());

    if($this->awsEc2Service->deleteSecurityGroup([
      'GroupId'   => $entity->group_id(),
    ]) != NULL) {

      $message = $this->t('The @type "@group_name" has been deleted.', [
                    '@type'       => $entity->getEntityType()->getLabel(),
                    '@group_name' => $entity->group_name(),
                 ]);

      $entity->delete();
      $this->messenger->addMessage($message);
    }
    else {
      $message = $this->t('The @type "@group_name" couldn\'t delete.', [
        '@type'       => $entity->getEntityType()->getLabel(),
        '@group_name' => $entity->group_name(),
      ]);
      $this->messenger->addError($message);
    }

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
