<?php

// Updated by yas 2016/06/01
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20.
namespace Drupal\aws_cloud\Aws\Config;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a Config entity.
 */
interface ConfigInterface extends ConfigEntityInterface {

  /**
   * The Cloud Context.
   */
  public function cloud_context();

  /**
   * The Cloud Type.
   */
  public function cloud_type();

  /**
   * Description.
   */
  public function description();

  /**
   * API version.
   */
  public function api_version();

  /**
   * API Endpoint URI.
   */
  public function endpoint();

  /**
   * AWS Region.
   */
  public function region();

  /**
   * Access Key.
   */
  public function aws_access_key();

  /**
   * Secret Key.
   */
  public function aws_secret_key();

  /**
   * User ID.
   */
  public function user_id();

  /**
   * Image Upload URL.
   */
  public function image_upload_url();

  /**
   * Image Register URL.
   */
  public function image_register_url();

  /**
   * X.509 Certificate.
   */
  public function certificate();

  /**
   * Date created.
   */
  public function created();

  /**
   * Date updated.
   */
  public function changed();

  /**
   * The comment language code.
   */
  public function langcode();

}
