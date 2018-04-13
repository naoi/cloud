<?php

// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Created by yas 2016/05/19.
namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form controller for the NetworkInterface entity create form.
 *
 * @ingroup aws_cloud
 */
class NetworkInterfaceCreateForm extends AwsCloudContentForm {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    /* @var $entity \Drupal\aws_cloud\Entity\Ec2\NetworkInterface */
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

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#cols'          => 60,
      '#rows'          => 3,
      '#default_value' => $entity->description(),
      '#weight'        => -5,
      '#required'      => FALSE,
    ];

    $form['subnet_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Subnet'),
      '#size'          => 60,
      '#default_value' => $entity->subnet_id(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['primary_private_ip'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Primary Private IP'),
      '#size'          => 60,
      '#default_value' => $entity->primary_private_ip(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['secondary_private_ips'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Secondary Private IPs (Comma (,) separated.)'),
      '#size'          => 60,
      '#default_value' => $entity->secondary_private_ips(),
      '#weight'        => -5,
      '#required'      => TRUE,
    ];

    $form['is_primary'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Primary or Not'),
      '#size'          => 60,
      '#default_value' => $entity->primary(),
      '#weight'        => -5,
    ];

    $form['security_groups'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Security Groups'),
      '#size'          => 60,
      '#default_value' => $entity->security_groups(),
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

    $result = $this->awsEc2Service->createNetworkInterface([
      'SubnetId'                       => $entity->subnet_id(),
      // PrivateIpAddresses is an array and required.
      'PrivateIpAddress'               => $entity->primary_private_ip(),
      // Groups is an array.
      'Groups'                         => [$entity->security_groups()],
      // PrivateIpAddresses is an array and PrivateIpAddress is required.
      'PrivateIpAddresses'             => [
        [
          'PrivateIpAddress' => $entity->secondary_private_ips(),  // REQUIRED
          'Primary'          => $entity->primary() ? true : false, // TRUE or FALSE
        ],
      ],
      'SecondaryPrivateIpAddressCount' => count(explode(',', $entity->secondary_private_ips())),
      'Description'                    => $entity->description(),
    ]);

    if (isset($result['NetworkInterfaceId'])
    && ($entity->setNetworkInterfaceId($result['NetworkInterfaceId']))
    && ($entity->setStatus($result['Status']))
    && ($entity->setVpcId($result['VpcId']))
    && ($entity->save())) {

      $message = $this->t('The @type "@label (@network_interface_id)" has been created.', [
        '@type'                 => $entity->getEntityType()->getLabel(),
        '@label'                => $entity->label(),
        '@network_interface_id' => $result['NetworkInterfaceId'],
      ]);
      $this->messenger->addMessage($message);
      $form_state->setRedirect('entity.aws_cloud_network_interface.collection', ['cloud_context' => $entity->cloud_context()]);
    }
    else {
      $message = $this->t('The @type "@label" couldn\'t create.', [
        '@type' => $entity->getEntityType()->getLabel(),
        '@label' => $entity->label(),
      ]);
      $this->messenger->addError($message);
    }
  }

}
