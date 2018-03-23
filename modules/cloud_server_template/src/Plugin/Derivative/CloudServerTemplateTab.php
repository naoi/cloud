<?php


namespace Drupal\cloud_server_template\Plugin\Derivative;

use Drupal\Core\Menu\LocalTaskDefault;
use Drupal\Core\Routing\RouteMatchInterface;

class CloudServerTemplateTab extends LocalTaskDefault {

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    $parameters = parent::getRouteParameters($route_match);
    $template = \Drupal::entityTypeManager()->getStorage('cloud_server_template')->load($parameters['cloud_server_template']);
    $parameters['cloud_context'] = $template->cloud_context();
    return $parameters;
  }
}
