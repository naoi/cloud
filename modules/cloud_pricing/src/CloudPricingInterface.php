<?php

/**
 * @file
 * Contains Drupal\cloud_pricing\CloudPricingInterface.
 */

namespace Drupal\cloud_pricing;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a CloudPricing entity.
 */
interface CloudPricingInterface extends ConfigEntityInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * The Cloud Pricing Cloud Context.
   */
  public function cloud_context();

  /**
   * The Cloud Pricing Cloud Context.
   */
  public function instance_type();

   /**
    * The Cloud Pricing Linux Usage.
    */
  public function linux_usage();

   /**
    * The Cloud Pricing Windows Usage.
    */
  public function windows_usage();

   /**
    * The Cloud Pricing Description.
    */
  public function description();

   /**
    * The comment language code.
    */
  public function langcode();
}
