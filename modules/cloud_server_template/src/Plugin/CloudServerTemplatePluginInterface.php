<?php

namespace Drupal\cloud_server_template\Plugin;

use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;

/**
 * Common interfaces for a cloud server template
 * @package Drupal\cloud_server_template\Plugin
 */
interface CloudServerTemplatePluginInterface {

  /**
   * Get the entity bundle defined for a particular plugin
   *
   * @return string
   *  The entity bundle used to store and interact with a particular cloud
   */
  public function getEntityBundleName();

  /**
   * Method is responsible for interacting with the implementing cloud's launch
   * functionality.
   * The server template contains all the information needed for that particular cloud
   *
   * @param \Drupal\cloud_server_template\Entity\CloudServerTemplateInterface $cloud_server_template
   *   A Cloud Server Template  object.
   *
   * @return array
   *  An associative array with a redirect route and any parameters to build the route.
   */
  public function launch(CloudServerTemplateInterface $cloud_server_template);

}