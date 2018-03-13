<?php

namespace Drupal\aws_cloud\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

class CloudServerTemplateDeriver extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    // TODO: inject cloud_context via dependency injection
    $cloud_contexts = \Drupal::entityTypeManager()->getListBuilder('cloud_context')->load();
    foreach ($cloud_contexts as $context) {
      $this->derivatives[$context->id()] = $base_plugin_definition;
      // supply the cloud_context.
      $this->derivatives[$context->id()]['cloud_context'] = $context->id();
    }
    return $this->derivatives;
  }
}