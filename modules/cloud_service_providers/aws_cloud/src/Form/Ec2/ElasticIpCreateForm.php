<?php

// Updated by yas 2016/06/24
// Updated by yas 2016/06/03
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/23
// Updated by yas 2016/05/21
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the ElasticIp entity create form.
 *
 * @ingroup aws_cloud
 */
class ElasticIpCreateForm extends AwsCloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\ElasticIp */
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
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['domain'] = [
      '#type'          => 'select',
      '#options'       => ['standard' => 'standard',
                                'vpc' => 'vpc',
                          ],
      '#title'         => $this->t('Domain (standard | vpc)'),
      '#default_value' => 'standard',
      '#required'      => TRUE,
      '#weight'        => -5,
    ];

    $form['actions'] = $this->actions($form, $form_state, $cloud_context);

    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $result = $this->awsEc2Service->allocateAddress([
      'Domain' => $entity->domain(),
    ]);

    if (isset($result['PublicIp'])
    && ($entity->setPublicIp($result['PublicIp']))
    && ($entity->setAllocationId($result['AllocationId']))
    && ($entity->save())) {
      $message = $this->t('The @label "%label (@elastic_ip)" has been created.', [
        '@label'      => $entity->getEntityType()->getLabel(),
        '%label'      => $entity->label(),
        '@elastic_ip' => $result['PublicIp'],
      ]);
      $this->messenger->addMessage($message);
    }
    else {
      $message = $this->t('The @label "%label" couldn\'t create.', [
        '@label' => $entity->getEntityType()->getLabel(),
        '%label' => $entity->label()
      ]);
      $this->messenger->addError($message);
    }

    $form_state->setRedirect('entity.aws_cloud_elastic_ip.collection', ['cloud_context' => $entity->cloud_context()]);
  }

}
