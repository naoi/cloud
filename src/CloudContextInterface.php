<?php

/**
 * Defines a common interface for cloud based entities to have
 * cloud context.
 */
namespace Drupal\cloud;


interface CloudContextInterface {

  /**
   * Gets the cloud_context from the entity
   *
   * @return string
   *  Cloud context string
   */
  public function cloud_context();

  /**
   * Sets the cloud_context
   *
   * @param string $cloud_context
   *   Cloud context string
   */
  public function setCloudContext($cloud_context);
}