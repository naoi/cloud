<?php

namespace Drupal\cloud_server_template\Plugin;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Defines an interface for cloud_server_template_plugin managers.
 */
interface CloudServerTemplatePluginManagerInterface extends PluginManagerInterface {

  /**
   * Load a plugin using the cloud_context
   * @param $cloud_context
   * @return
   *  loaded CloudServerTemplatePlugin
   */
  public function loadPluginVariant($cloud_context);

}
