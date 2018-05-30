<?php

namespace Drupal\cloud\Plugin;

/**
 * Common interfaces for cloud config plugins
 * @package Drupal\cloud\Plugin
 */
interface CloudConfigPluginInterface {

  /**
   * Load all config entities.
   *
   * @return array
   *   An array of cloud_config entities
   */
  public function loadConfigEntities();

  /**
   * Load a single cloud_config entity
   * @param string $cloud_context
   *   The cloud_context to load the entity from
   * @return mixed
   */
  public function loadConfigEntity($cloud_context);

  /**
   * Load credentials for a given cloud context
   * @param $cloud_context
   * @return mixed
   */
  public function loadCredentials($cloud_context);

}