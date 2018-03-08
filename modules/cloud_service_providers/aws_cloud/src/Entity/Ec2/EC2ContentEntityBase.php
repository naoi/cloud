<?php

namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Contains common methods for AWS Cloud entities.
 *
 * The main purpose of this class is to provide the
 * cloud_context as a parameter in urlRouteParameters method.
 */
class EC2ContentEntityBase extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function cloud_context() {
    return $this->get('cloud_context')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCloudContext($cloud_context) {
    $this->set('cloud_context', $cloud_context);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = [];

    if (!in_array($rel, ['collection', 'add-page', 'add-form'], TRUE)) {
      // The entity ID is needed as a route parameter.
      $uri_route_parameters[$this->getEntityTypeId()] = $this->id();
    }
    if ($rel === 'add-form' && ($this->getEntityType()->hasKey('bundle'))) {
      $parameter_name = $this->getEntityType()->getBundleEntityType() ?: $this->getEntityType()->getKey('bundle');
      $uri_route_parameters[$parameter_name] = $this->bundle();
    }
    if ($rel === 'revision' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    // add in cloud context
    $uri_route_parameters['cloud_context'] = $this->cloud_context();

    return $uri_route_parameters;
  }

}