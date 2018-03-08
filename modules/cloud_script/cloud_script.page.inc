<?php

/**
 * @file
 * Contains cloud_script.page.inc.
 *
 * CloudScript page callback file for the cloud_script entity.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for cloud_script templates.
 *
 * Default template: cloud_script.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_cloud_script(array &$variables) {
  // Fetch CloudScript Entity Object.
  $cloud_script = $variables['elements']['#cloud_script'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
