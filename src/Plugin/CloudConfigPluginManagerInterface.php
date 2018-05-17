<?php

namespace Drupal\cloud\Plugin;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for cloud_config_plugin managers.
 */
interface CloudConfigPluginManagerInterface extends PluginManagerInterface {

  /**
   * Set cloud_context
   * @param String $cloud_context
   * @return mixed
   */
  public function setCloudContext($cloud_context);

  /**
   * Load all configuration entities for a given bundle
   *
   * @param String $entity_bundle
   *  The bundle to load
   * @return mixed
   */
  public function loadConfigEntities($entity_bundle);

  /**
   * Load a plugin using the cloud_context
   * @return
   *  loaded CloudConfigPlugin
   */
  public function loadPluginVariant();

  /**
   * Load a config entity
   * @return mixed
   */
  public function loadConfigEntity();

  /**
   * Load credentials
   * @return mixed
   */
  public function loadCredentials();

}
