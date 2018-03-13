<?php

namespace Drupal\cloud_server_template\Plugin;

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

}