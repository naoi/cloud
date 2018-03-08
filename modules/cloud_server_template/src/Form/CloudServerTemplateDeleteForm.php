<?php

// Updated by yas 2015/06/01
// updated by yas 2015/05/31
// created by yas 2015/05/30.
namespace Drupal\cloud_server_template\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a CloudServerTemplate entity.
 *
 * @ingroup cloud_server_template
 */
class CloudServerTemplateDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    $entity = $this->entity;
    return t('Are you sure you want to delete entity %name?', [
      '%name' => $entity->label(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    $entity = $this->entity;
    $url = new Url('entity.cloud_server_template.collection');
    $url->setRouteParameter('cloud_context', $entity->cloud_context());
    return $url;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->delete();

    drupal_set_message(
      $this->t('content @type: deleted @label.',
        [
          '@type'  => $entity->bundle(),
          '@label' => $entity->label(),
        ]
        )
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
