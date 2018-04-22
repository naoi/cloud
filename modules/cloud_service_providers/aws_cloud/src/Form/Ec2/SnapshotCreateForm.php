<?php

// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/21
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the Snapshot entity create form.
 *
 * @ingroup aws_cloud
 */
class SnapshotCreateForm extends AwsCloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\Snapshot */
    $form = parent::buildForm($form, $form_state);
    $this->awsEc2Service->setCloudContext($cloud_context);
    $entity = $this->entity;

    $form['cloud_context'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Cloud ID'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => !$entity->isNew()
      ? $entity->cloud_context()
      : $cloud_context,
      '#required'      => TRUE,
      '#weight'        => -5,
      '#disabled'      => TRUE,
    ];

    $form['name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Name'),
      '#maxlength'     => 255,
      '#size'          => 60,
      '#default_value' => $entity->label(),
      '#required'      => FALSE,
      '#weight'        => -5,
    ];

    $form['volume_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Volume ID'),
      '#size'          => 60,
      '#default_value' => $entity->volume_id(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#maxlength'     => 255,
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['langcode'] = [
      '#title' => t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $result = $this->awsEc2Service->createSnapshot([
      'VolumeId'    => $entity->volume_id(),
      'Description' => $entity->description(),
    ]);

    if (isset($result['SnapshotId'])
    && ($entity->setSnapshotId($result['SnapshotId']))
    && ($entity->setStatus($result['State']))
    && ($entity->setStarted(strtotime($result['StartTime'])))
    && ($entity->setEncrypted($result['Encrypted']))
    && ($entity->save())) {

      $message = $this->t('The @label "%label (@snapshot_id)" has been created.', [
        '@label'       => $entity->getEntityType()->getLabel(),
        '%label'       => $entity->label(),
        '@snapshot_id' => $result['SnapshotId'],
      ]);

      $this->messenger->addMessage($message);
      $form_state->setRedirect('view.aws_snapshot.page_1', ['cloud_context' => $entity->cloud_context()]);
    }
    else {
      $message = $this->t('The @label "%label" couldn\'t create.', [
        '@label' => $entity->getEntityType()->getLabel(),
        '%label' => $entity->label(),
      ]);
      $this->messenger->addError($message);
    }


  }

}
