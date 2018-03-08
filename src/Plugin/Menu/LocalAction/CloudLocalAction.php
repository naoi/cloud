<?php

// Created by yas 2015/06/14.

/**
 * @file
 * Contains \Drupal\cloud\Plugin\Menu\LocalAction\CloudLocalAction.
 */
namespace Drupal\cloud\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;

/**
 * Defines a local action plugin with a dynamic title.
 */
class CloudLocalAction extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->t('My @arg action', ['@arg' => 'dynamic-title']);
  }


  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = [];
    // Declares a single route under the name 'cloud.content'.
    // Returns an array of Route objects.
//  for($i = 0, $i < 3; $i++) {
      $routes['entity.aws_cloud_instance.collection'] = new Route(
        // Path to attach this route to:
        '/clouds/aws_cloud/{cloud_context}/instance',
        // Route defaults:
        [
          '_entity_list' => 'aws_cloud_instance',
          '_title' => 'AWS Cloud Instance 2'
        ],
        // Route requirements:
        [
          '_permission'  => 'access content',
        ]
      );
//  }

    return $routes;
  }
}