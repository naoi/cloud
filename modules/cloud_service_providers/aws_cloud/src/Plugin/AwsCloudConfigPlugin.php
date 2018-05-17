<?php

namespace Drupal\aws_cloud\Plugin;

use Drupal\cloud\Plugin\CloudConfigPluginInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class AwsCloudConfigPlugin extends PluginBase implements CloudConfigPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * @var EntityTypeManagerInterface $entityTypeManager.
   */
  protected $entityTypeManager;

  /**
   * AwsCloudConfigPlugin constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, Messenger $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('messenger')
    );
  }

  /**
   * Load all entities for a given entity type and bundle
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function loadConfigEntities() {
    return $this->entityTypeManager->getStorage($this->pluginDefinition['entity_type'])->loadByProperties(['type' => [$this->pluginDefinition['entity_bundle']]]);
  }

  /**
   * Load an array of credentials
   * @param $cloud_context
   * @return array
   */
  public function loadCredentials($cloud_context) {
    /* @var \Drupal\cloud\Entity\CloudConfig $entity */
    $entity = $this->loadConfigEntity($cloud_context);
    $credentials = [];
    if ($entity != FALSE) {
      // there should only be one
      $credentials['credentials'] = [
        'key' => $entity->get('field_access_key')->value,
        'secret' => $entity->get('field_secret_key')->value,
      ];
      $credentials['region'] = $entity->get('field_region')->value;
      $credentials['version'] = $entity->get('field_api_version')->value;
      $credentials['endpoint'] = $entity->get('field_api_endpoint_uri')->value;
    }
    return $credentials;
  }

  /**
   * Load a cloud config entity
   * @param string $cloud_context
   * @return bool|mixed
   */
  public function loadConfigEntity($cloud_context) {
    $entity = $this->entityTypeManager->getStorage($this->pluginDefinition['entity_type'])->loadByProperties(['cloud_context' => [$cloud_context]]);
    if (count($entity) == 1){
      return array_shift($entity);
    }
    return FALSE;
  }

}