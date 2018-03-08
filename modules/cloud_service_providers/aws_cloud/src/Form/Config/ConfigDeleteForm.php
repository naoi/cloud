<?php

// Updated by yas 2016/05/25
// updated by yas 2016/05/20
// updated by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Config;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Builds the form to delete a AwsCloud.
 */
class ConfigDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    $entity = $this->entity;
    return $this->t('Are you sure you want to delete %name?', ['%name' => $entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.cloud_context.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->delete();

    drupal_set_message(
      $this->t('content @type: deleted @label.', [
          '@type'  => $entity->bundle(),
          '@label' => $entity->label(),
        ])
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
