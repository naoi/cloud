<?php

namespace Drupal\cloud_server_template\Plugin;

use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;

/**
 * Common interfaces for a cloud server template
 * @package Drupal\cloud_server_template\Plugin
 */
interface CloudServerTemplatePluginInterface {

  /**
   * @return string
   *  The entity bundle used to store and interact with a particular cloud
   */
  public function getEntityBundleName();

  /**
   * Function for launching from a concrete plugin
   * @return mixed
   */
  public function launch(CloudServerTemplateInterface $cloud_server_template);

}