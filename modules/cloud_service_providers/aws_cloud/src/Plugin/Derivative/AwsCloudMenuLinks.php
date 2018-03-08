<?php

// Updated by yas 2016/06/23
// Updated by yas 2016/06/22
// Created by yas 2016/06/21.

namespace Drupal\aws_cloud\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides plugin definitions for custom local menu.
 *
 * @see \Drupal\aws_cloud\Plugin\Derivative\AwsCloudLocalTasks
 */
class AwsCloudMenuLinks extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The config storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $config_storage;

  /**
   * Constructs new AwsCloudLocalTasks.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $cloud_context
   *   The config storage.
   */
  public function __construct(EntityStorageInterface $config_storage) {
    $this->config_storage = $config_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity.manager')->getStorage('cloud_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    foreach ($this->config_storage->loadMultiple() as $cloud_context => $entity) {

      // Building Local Tasks
      $id = $entity->getEntityType()->id() . '.local_tasks.' . $cloud_context;
      $this->derivatives[$id] = $base_plugin_definition;
      $this->derivatives[$id]['title'] = $entity->label();
      $this->derivatives[$id]['route_name'] = 'entity.aws_cloud_instance.collection';
      $this->derivatives[$id]['base_route'] = 'cloud.service_providers.menu';
      $this->derivatives[$id]['parent'] = 'cloud.service_providers.menu';
      $this->derivatives[$id]['route_parameters'] = ['cloud_context' => $cloud_context];
    }

    foreach ($this->derivatives as &$entry) {
      $entry += $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
