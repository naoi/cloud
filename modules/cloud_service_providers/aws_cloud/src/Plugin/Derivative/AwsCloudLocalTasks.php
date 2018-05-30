<?php

// Updated by yas 2016/06/23
// Created by yas 2016/06/21.

namespace Drupal\aws_cloud\Plugin\Derivative;

use Drupal\cloud\Plugin\CloudConfigPluginManagerInterface;
use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides plugin definitions for custom local menu.
 *
 * @see \Drupal\aws_cloud\Plugin\Derivative\AwsCloudLocalTasks
 */
class AwsCloudLocalTasks extends DeriverBase implements ContainerDeriverInterface {

  /**
   * CloudConfigPlugin
   * @var \Drupal\cloud\Plugin\CloudConfigPluginManagerInterface
   */
  protected $cloudConfigPluginManager;

  /**
   * Constructs new AwsCloudLocalTasks.
   *
   * @param \Drupal\cloud\Plugin\CloudConfigPluginManagerInterface $cloud_config_plugin_manager
   *   The config plugin manager
   */
  public function __construct(CloudConfigPluginManagerInterface $cloud_config_plugin_manager) {
    $this->cloudConfigPluginManager = $cloud_config_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('plugin.manager.cloud_config_plugin')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $entities = $this->cloudConfigPluginManager->loadConfigEntities('aws_ec2');

    foreach ($entities as $entity) {
      /* @var \Drupal\cloud\Entity\CloudConfig $entity */
      $id = $entity->id() . '.local_tasks.' . $entity->cloud_context();
      $this->derivatives[$id] = $base_plugin_definition;
      $this->derivatives[$id]['title'] = $entity->label();
      $this->derivatives[$id]['route_name'] = 'entity.aws_cloud_instance.collection';
      $this->derivatives[$id]['route_parameters'] = ['cloud_context' => $entity->cloud_context()];
    }

    return $this->derivatives;
  }

}
