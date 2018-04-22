<?php

namespace Drupal\cloud_server_template\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Cloud Server Template edit forms.
 *
 * @ingroup cloud_server_template
 */
class CloudServerTemplateForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\cloud_server_template\Entity\CloudServerTemplate */
    $form = parent::buildForm($form, $form_state);

    if (!$this->entity->isNew()) {
      $form['new_revision'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 99,
      ];
    }

    $entity = $this->entity;

    // setup the cloud_context based on value passed in the path
    $form['cloud_context']['#disabled'] = TRUE;
    if ($entity->isNew()) {
      $form['cloud_context']['widget'][0]['value']['#default_value'] = $cloud_context;
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime(REQUEST_TIME);
      $entity->setRevisionUserId(\Drupal::currentUser()->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Cloud Server Template.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Cloud Server Template.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.cloud_server_template.canonical', ['cloud_server_template' => $entity->id(), 'cloud_context' => $entity->cloud_context()]);
  }

}
