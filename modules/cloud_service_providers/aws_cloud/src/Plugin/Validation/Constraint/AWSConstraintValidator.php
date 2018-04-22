<?php

namespace Drupal\aws_cloud\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Perform AWS specific validations
 */
class AWSConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    // only perform validations for aws_cloud bundles
    if ($entity->bundle() == 'aws_cloud') {
      $instance_type = $entity->field_instance_type->value;
      $field_network = $entity->field_network->entity;
      $image = $entity->field_image_id->entity;
      $shutdown = $entity->field_instance_shutdown_behavior->value;

      // make sure a network is specified when launching a t2.* instance
      if (strpos($instance_type, 't2.') !== FALSE) {
        if (!isset($field_network)) {
          $this->context->buildViolation($constraint->noNetwork, ['%instance_type' => $instance_type])->atPath('field_network')->addViolation();
        }
      }

      // if the image is an instance-store, it cannot use stop for shutdown
      if ($image->root_device_type->value == 'instance-store' && isset($shutdown)) {
        if ($shutdown == 'stop') {
          $this->context->buildViolation($constraint->shutdownBehavior)->atPath('field_instance_shutdown_behavior')->addViolation();
        }
      }
    }
  }
}