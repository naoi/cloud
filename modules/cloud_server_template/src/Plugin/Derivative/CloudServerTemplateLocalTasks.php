<?php

// Created by yas 2016/06/23.

namespace Drupal\cloud_server_template\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides plugin definitions for custom local menu.
 *
 * @see \Drupal\cloud_server_template\Plugin\Derivative\CloudServerTemplateLocalTasks
 */
class CloudServerTemplateLocalTasks extends DeriverBase implements ContainerDeriverInterface {

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
      $container->get('entity.manager')->getStorage('cloud_server_template')
    );
  }

  /**
   * {@inheritdoc}
   */
//  public function getDerivativeDefinitions($base_plugin_definition) {
//
//    foreach ($this->config_storage->loadMultiple() as $cloud_server_template => $entity) {
//
//      // Building Local Tasks
//      $cloud_context = $entity->cloud_context();
//      if (empty($cloud_context)) {
//
//        continue; // Skip if cloud_context is empty.
//      }
//
//      $id = $entity->getEntityType()->id() . '.local_tasks.' . $cloud_context;
//      $this->derivatives[$id] = $base_plugin_definition;
//      $this->derivatives[$id]['title'] = $entity->label();
//      $this->derivatives[$id]['route_name'] = 'entity.cloud_server_template.collection';
//      $this->derivatives[$id]['base_route'] = 'cloud_server_template.local_tasks.cloud_context.list_all';
//      $this->derivatives[$id]['route_parameters'] = ['cloud_context' => $cloud_context];
//    }
///**
//  @TODO: Maybe unnecessary code, Clean up later
//    foreach ($this->derivatives as &$entry) {
//      $entry += $base_plugin_definition;
//    }
//*/
//
//    return $this->derivatives;
//  }

}
