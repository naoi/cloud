<?php

/**
 * @file
 * Contains cloud_alert.page.inc.
 *
 * CloudAlert page callback file for the cloud_alert entity.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for cloud_alert templates.
 *
 * Default template: cloud_alert.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_cloud_alert(array &$variables) {
  // Fetch CloudAlert Entity Object.
  $cloud_alert = $variables['elements']['#cloud_alert'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
