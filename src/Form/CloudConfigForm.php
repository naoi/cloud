<?php

namespace Drupal\cloud\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Cloud config edit forms.
 *
 * @ingroup cloud
 */
class CloudConfigForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\cloud\Entity\CloudConfig */
    $form = parent::buildForm($form, $form_state);

    if (!$this->entity->isNew()) {
      $form['new_revision'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 22,
      ];
    }

    $form['cloud_context'] = [
      '#type' => 'machine_name',
      '#title' => t('Cloud Provider Machine Name'),
      '#description' => t('A unique machine name for the cloud provider.'),
      '#default_value' => $this->entity->cloud_context(),
      '#disabled' => !$this->entity->isNew(),
      '#machine_name' => [
        'exists' => [$this->entity, 'checkCloudContext'],
      ]
    ];

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
        drupal_set_message($this->t('Created the %label Cloud config.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Cloud config.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.cloud_config.canonical', ['cloud_config' => $entity->id()]);
  }

}
