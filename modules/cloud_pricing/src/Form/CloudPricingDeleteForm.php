<?php

// Changed by yas 2015/06/08
// created by yas 2015/06/02.
namespace Drupal\cloud_pricing\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Builds the form to delete a CloudPricing.
 */
class CloudPricingDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    $entity = $this->entity;
    return $this->t('Are you sure you want to delete %instance_type?', [
      '%instance_type' => $entity->instance_type(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    $entity = $this->entity;
    $url = new Url('entity.cloud_pricing.collection');
    $url->setRouteParameter('cloud_context', $entity->cloud_context());
    return $url;
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

    /* @FIXME:
    // User Activity Log
    cloud_audit_user_activity(
    array(
    'type' => 'user_activity',
    'message' => t('Pricing has been deleted: @pricing', array('@pricing' => $pricing_obj->instance_type)),
    'link' => '',
    )
    );
     */
    drupal_set_message(
      $this->t('content @type: deleted @instance_type.',
        [
          '@type' => $entity->bundle(),
          '@instance_type' => $entity->instance_type(),
        ]
        )
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
