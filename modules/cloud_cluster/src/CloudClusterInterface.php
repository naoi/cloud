<?php

// Created by yas 2016/05/25.
namespace Drupal\cloud_cluster;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a CloudCluster entity.
 *
 * @ingroup cloud_cluster
 */
interface CloudClusterInterface extends ContentEntityInterface, EntityOwnerInterface {

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function created();

  /**
   * {@inheritdoc}
   */
  public function changed();

  /**
   * The comment language code.
   */
  public function langcode();

}
