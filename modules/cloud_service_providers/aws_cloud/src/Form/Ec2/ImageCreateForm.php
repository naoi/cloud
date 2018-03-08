<?php

// Updated by yas 2016/06/12
// Updated by yas 2016/05/31
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/23
// Updated by yas 2016/05/21
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\aws_cloud\Controller\Ec2\ApiController;

/**
 * Form controller for the Image entity create form.
 *
 * @ingroup aws_cloud
 */
class ImageCreateForm extends CloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\Image */
    $form = parent::buildForm($form, $form_state);

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

    $form['instance_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Instance ID'),
      '#maxlength'     => 15,
      '#size'          => 60,
      '#default_value' => $entity->instance_id(),
      '#required'      => TRUE,
      '#weight'        => -5,
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

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $apiController = new ApiController($this->query_factory);
    $result = $apiController->createImage($entity);

    $status  = 'error';
    $message = $this->t('The @label "%label" couldn\'t create.', [
      '@label' => $entity->getEntityType()->getLabel(),
      '%label' => $entity->label(),
    ]);

    if (isset($result['ImageId'])
    && ($entity->setImageId($result['ImageId']))
    && ($entity->save())) {

      $status  = 'status';
      $message = $this->t('The @label "%label (@image_id)" has been created.', [
        '@label'    => $entity->getEntityType()->getLabel(),
        '%label'    => $entity->label(),
        '@image_id' => $entity->image_id(),
      ]);

      $form_state->setRedirectUrl($entity
                 ->urlInfo('collection')
                 ->setRouteParameter('cloud_context', $entity->cloud_context()));
    }

    drupal_set_message($message, $status);
  }

}
