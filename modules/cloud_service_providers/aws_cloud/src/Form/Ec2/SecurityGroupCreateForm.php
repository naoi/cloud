<?php

// Updated by yas 2016/09/11
// Updated by yas 2016/06/04
// Updated by yas 2016/06/03
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\aws_cloud\Entity\Config\Config;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the SecurityGroup entity create form.
 *
 * @ingroup aws_cloud
 */
class SecurityGroupCreateForm extends AwsCloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    $cloudContext = Config::load($cloud_context);

    if(!isset($cloudContext)) {
      $message = $this->t("Not found: AWS Cloud provider '@cloud_context'", [
        '@cloud_context'  => $cloud_context,
      ]);
      $this->messenger->addError($message);
    }

    $this->awsEc2Service->setCloudContext($cloud_context);

    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\SecurityGroup */
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

    $form['group_name'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Security Group Name'),
      '#size'          => 60,
      '#default_value' => $entity->group_name(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $vpcs = $this->awsEc2Service->getVpcs();
    $vpcs[$entity->vpc_id()] = 'N/A';
    ksort($vpcs);
    $form['vpc_id'] = [
      '#type'          => 'select',
      '#title'         => $this->t('VPC CIDR (ID)'),
      '#options'       => $vpcs,
      '#default_value' => $entity->vpc_id(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => TRUE,
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

    $result = $this->awsEc2Service->createSecurityGroup([
      'GroupName'   => $entity->group_name(),
      'VpcId'       => $entity->vpc_id(),
      'Description' => $entity->description(),
    ]);

    if (isset($result['GroupId'])
    && ($entity->setGroupId($result['GroupId']))
    && ($entity->save())) {

      $status  = 'status';
      $message = $this->t('The @label "@group_name" has been created.', [
        '@label'      => $entity->getEntityType()->getLabel(),
        '@group_name' => $entity->group_name(),
      ]);

      $form_state->setRedirect('entity.aws_cloud_security_group.collection', ['cloud_context' => $entity->cloud_context()]);
      $this->messenger->addMessage($message);
    }
    else {
      $message = $this->t('The @label "@group_name" couldn\'t create.', [
        '@label'      => $entity->getEntityType()->getLabel(),
        '@group_name' => $entity->group_name(),
      ]);
      $this->messenger->addError($message);
    }
  }

}
