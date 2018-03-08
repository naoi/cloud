<?php

// @TODO: THIS IS A TRIAL. DELETE ME LATER.

namespace Drupal\aws_cloud\Plugin\Menu;

class AwsCloudTab extends LocalTaskDefault {

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    return ['cloud_context' => $route_match->getParameter('cloud_context')];
  }

}