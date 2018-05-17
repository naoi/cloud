<?php

namespace Drupal\cloud_server_template\Plugin\Derivative;

use Drupal\cloud\Plugin\CloudConfigPluginManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CloudServerTemplateCloudContextBundleDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entity_type_bundle_info;

  /**
   * CloudConfigPlugin
   * @var \Drupal\cloud\Plugin\CloudConfigPluginManagerInterface
   */
  protected $cloudConfigPluginManager;

  /**
   * Constructs new AwsCloudLocalTasks.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *  The entity type manager
   * @param \Drupal\cloud\Plugin\CloudConfigPluginManagerInterface $cloud_config_plugin_manager
   *  The cloud config plugin manager
   */
  public function __construct(EntityTypeBundleInfoInterface $entity_type_bundle_info, CloudConfigPluginManagerInterface $cloud_config_plugin_manager) {
    $this->entity_type_bundle_info = $entity_type_bundle_info;
    $this->cloudConfigPluginManager = $cloud_config_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.bundle.info'),
      $container->get('plugin.manager.cloud_config_plugin')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    // loop through all cloud_config bundles and build server template links
    // @TODO: not all implementing clouds will have server_templates
    // @TODO: Add support for identifying which clouds have server_templates
    $bundles = $this->entity_type_bundle_info->getBundleInfo('cloud_config');
    foreach ($bundles as $key => $bundle) {
      $entities = $this->cloudConfigPluginManager->loadConfigEntities($key);
      foreach ($entities as $entity) {
        /* @var \Drupal\cloud\Entity\CloudConfig $entity */
        $this->derivatives[$entity->cloud_context()] = $base_plugin_definition;
        $this->derivatives[$entity->cloud_context()]['cloud_context'] = $entity->cloud_context();
      }
    }
    return $this->derivatives;
  }
}