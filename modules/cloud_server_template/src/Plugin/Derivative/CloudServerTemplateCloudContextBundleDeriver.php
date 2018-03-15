<?php

namespace Drupal\cloud_server_template\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CloudServerTemplateCloudContextBundleDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The config storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $config_storage;

  /**
   * Constructs new CloudServerTemplateCloudContextBundleDeriver.
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
    // TODO: inject cloud_context via dependency injection
    //$cloud_contexts = \Drupal::entityTypeManager()->getListBuilder('cloud_context')->load();
    foreach ($this->config_storage->loadMultiple() as $cloud_context => $context) {
    //foreach ($cloud_contexts as $context) {
      $this->derivatives[$context->id()] = $base_plugin_definition;
      // supply the cloud_context.
      $this->derivatives[$context->id()]['cloud_context'] = $context->id();
    }
    return $this->derivatives;
  }
}