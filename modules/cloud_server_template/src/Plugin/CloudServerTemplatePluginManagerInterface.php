<?php

namespace Drupal\cloud_server_template\Plugin;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;

/**
 * Defines an interface for cloud_server_template_plugin managers.
 */
interface CloudServerTemplatePluginManagerInterface extends PluginManagerInterface {

  /**
   * Load a plugin using the cloud_context
   *
   * @param $cloud_context
   * @return
   *  loaded CloudServerTemplatePlugin
   */
  public function loadPluginVariant($cloud_context);

  /**
   * Launch a cloud server template
   *
   * @param \Drupal\cloud_server_template\Entity\CloudServerTemplateInterface $cloud_server_template
   * @return mixed
   */
  public function launch(CloudServerTemplateInterface $cloud_server_template);

}
