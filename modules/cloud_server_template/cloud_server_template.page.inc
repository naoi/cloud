<?php

/**
 * @file
 * Contains cloud_server_template.page.inc.
 *
 * CloudServerTemplate page callback file for the cloud_server_template entity.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for cloud_server_template templates.
 *
 * Default template: cloud_server_template.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_cloud_server_template(array &$variables) {
  // Fetch CloudServerTemplate Entity Object.
  $cloud_server_template = $variables['elements']['#cloud_server_template'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
