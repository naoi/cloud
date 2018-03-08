<?php

// Created by yas 2015/06/03.
namespace Drupal\cloud_script;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a CloudScript entity.
 *
 * @ingroup cloud_script
 */
interface CloudScriptInterface extends ContentEntityInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * {@inheritdoc}
   */
  /*
  public function cloud_context();
   */

  /**
   * {@inheritdoc}
   */
  public function type();

  /**
   * {@inheritdoc}
   */
  public function description();

  /**
   * {@inheritdoc}
   */
  public function input_parameters();

  /**
   * {@inheritdoc}
   */
  public function script();

  /**
   * {@inheritdoc}
   */
  public function created();

  /**
   * {@inheritdoc}
   */
  public function changed();

  /**
   * {@inheritdoc}
   */
  /*
  public function setCloudContext($cloud_context);
   */

  /**
   * The comment language code.
   */
  public function langcode();

}
