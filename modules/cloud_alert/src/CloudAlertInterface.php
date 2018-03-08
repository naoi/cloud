<?php

// Updated by yas 2016/05/23.
namespace Drupal\cloud_alert;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a CloudAlert entity.
 *
 * @ingroup cloud_alert
 */
interface CloudAlertInterface extends ContentEntityInterface, EntityOwnerInterface {

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
