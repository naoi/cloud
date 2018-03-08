<?php

/**
 * @file
 * Contains cloud_cluster.page.inc.
 *
 * CloudCluster page callback file for the cloud_cluster entity.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for cloud_cluster templates.
 *
 * Default template: cloud_cluster.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_cloud_cluster(array &$variables) {
  // Fetch CloudCluster Entity Object.
  $cloud_cluster = $variables['elements']['#cloud_cluster'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
