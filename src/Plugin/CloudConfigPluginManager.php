<?php

namespace Drupal\cloud\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\cloud\CloudConfigPluginInterface;

/**
 * Provides the default cloud_config_plugin manager.
 */
class CloudConfigPluginManager extends DefaultPluginManager implements CloudConfigPluginManagerInterface {

  /**
   * Provides default values for all cloud_config_plugin plugins.
   *
   * @var array
   */
  protected $defaults = [
    'id' => 'cloud_config',
    'entity_type' => 'cloud_config',
  ];

  /**
   * @var String cloud_context
   */
  private $cloud_context;

  /**
   * @var \Drupal\cloud\Plugin\CloudConfigPluginInterface $plugin
   */
  private $plugin;

  /**
   * Constructs a new CloudConfigPluginManager object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend) {
    // Add more services as required.
    $this->moduleHandler = $module_handler;
    $this->setCacheBackend($cache_backend, 'cloud_config_plugin', ['cloud_config_plugin']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $this->discovery = new YamlDiscovery('cloud.config.plugin', $this->moduleHandler->getModuleDirectories());
      $this->discovery->addTranslatableProperty('label', 'label_context');
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }
    return $this->discovery;
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    if (empty($definition['id'])) {
      throw new PluginException(sprintf('Example plugin property (%s) definition "is" is required.', $plugin_id));
    }

    if (empty($definition['entity_bundle'])) {
      throw new PluginException(sprintf('entity_bundle property is required for (%s)', $plugin_id));
    }

    if (!isset($definition['base_plugin']) && empty($definition['cloud_context'])) {
      throw new PluginException(sprintf('cloud_context property is required for (%s)', $plugin_id));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setCloudContext($cloud_context) {
    $this->cloud_context = $cloud_context;
    // load the plugin variant since we know the cloud_context
    $this->plugin = $this->loadPluginVariant();
    if ($this->plugin == FALSE) {
      throw new CloudConfigException(sprintf('Cannot load cloud config plugin for %s', ['%s' => $cloud_context]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function loadPluginVariant() {
    $plugin = FALSE;
    foreach ($this->getDefinitions() as $key => $definition) {
      if ($definition['cloud_context'] == $this->cloud_context) {
        $plugin = $this->createInstance($key);
        break;
      }
    }
    return $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function loadConfigEntity() {
    $config_entity = $this->plugin->loadCloudConfigEntity($this->cloud_context);
    if ($config_entity == FALSE) {
      throw new CloudConfigPluginExeception(sprintf('Cannot load configuration entity for %s', ['%s' => $this->cloud_context]));
    }
    return $config_entity;
  }

  /**
   * {@inheritdoc}
   */
  public function loadConfigEntities($entity_bundle) {
    /* @var \Drupal\cloud\Plugin\CloudConfigPluginInterface $plugin */
    $plugin = $this->loadBasePluginDefinition($entity_bundle);
    if ($plugin == FALSE) {
      throw new CloudConfigPluginExeception(sprintf('Cannot load Cloud Config Entity for %s', ['%s' => $entity_bundle]));
    }
    return $plugin->loadConfigEntities();
  }

  /**
   * Helper method to load the base plugin definition.
   * Useful when there is no cloud_context
   *
   * @param String $entity_bundle
   * @return bool|object
   */
  private function loadBasePluginDefinition($entity_bundle) {
    $plugin = FALSE;
    foreach ($this->getDefinitions() as $key => $definition) {
      if (isset($definition['base_plugin']) && $definition['entity_bundle'] == $entity_bundle) {
        $plugin = $this->createInstance($key);
        break;
      }
    }
    return $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function loadCredentials() {
    return $this->plugin->loadCredentials($this->cloud_context);
  }
}
