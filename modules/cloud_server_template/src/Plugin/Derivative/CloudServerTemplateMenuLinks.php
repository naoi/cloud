<?php
/**
 * Created by PhpStorm.
 * User: baldwin
 * Date: 3/9/18
 * Time: 2:55 PM
 */

namespace Drupal\cloud_server_template\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CloudServerTemplateMenuLinks extends DeriverBase implements ContainerDeriverInterface {
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
      $id = 'entity.cloud_server_template.local_tasks.' . $cloud_context;
      $this->derivatives[$id] = $base_plugin_definition;
      $this->derivatives[$id]['title'] = $entity->label() . ' Server Templates';
      $this->derivatives[$id]['route_name'] = 'entity.cloud_server_template.collection.list_all.context';
      $this->derivatives[$id]['parent'] = 'cloud_server_template.menu';
      $this->derivatives[$id]['route_parameters'] = ['cloud_context' => $cloud_context];
    }
    return $this->derivatives;
  }
}