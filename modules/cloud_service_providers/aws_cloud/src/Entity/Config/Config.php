<?php

// Updated by yas 2016/06/01
// Updated by yas 2016/05/26
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2015/06/14
// Created by yas 2015/06/05.
namespace Drupal\aws_cloud\Entity\Config;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\aws_cloud\Aws\Config\ConfigInterface;

// Note: <strike>id = "aws_cloud" needs to match to *.routing.yml's parameter: {aws_cloud}</strike>.
// @ConfigEntityType( id = "..." - This is a machine name, so we cannot use a dot (.).
/**
 * Defines the Config entity.
 *
 * @ConfigEntityType(
 *   id    = "cloud_context",
 *   label = @Translation("AWS Cloud Provider information"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder"                ,
 *     "list_builder" = "Drupal\aws_cloud\Controller\Config\ConfigListBuilder",
 *     "form" = {
 *       "add"        = "Drupal\aws_cloud\Form\Config\ConfigEditForm"  ,
 *       "edit"       = "Drupal\aws_cloud\Form\Config\ConfigEditForm"  ,
 *       "delete"     = "Drupal\aws_cloud\Form\Config\ConfigDeleteForm"
 *     }
 *   },
 *   config_prefix = "cloud",
 *   entity_keys = {
 *     "id"    = "id"   ,
 *     "uuid"  = "uuid" ,
 *     "label" = "label",
 *   },
 *   links = {
 *     "edit-form"    = "/entity.cloud_context.edit_form.edit",
 *     "delete-form"  = "/entity.cloud_context.delete_form"   ,
 *     "collection"   = "/entity.cloud_context.collection"    ,
 *   },
 *   admin_permission = "administer aws cloud provider"
 * )
 */
class Config extends ConfigEntityBase implements ConfigInterface {

  /**
   * Entity bundle this module implements
   * @var string
   */
  private $entity_bundle = 'aws_cloud';

  /**
   * Cloud Display Name.
   *
   * @var string
   */
  protected $id;

  /**
   * Cloud Display Name.
   *
   * @var string
   */
  protected $label;

  /**
   * AWS Compatible Cloud Type.
   *
   * @var string
   */
  protected $cloud_type;

  /**
   * Description.
   *
   * @var string
   */
  protected $description;

  /**
   * API version.
   *
   * @var string
   */
  protected $api_version;

  /**
   * API Endpoint URL.
   *
   * @var string
   */
  protected $endpoint;

  /**
   * Region.
   *
   * @var string
   */
  protected $region;

  /**
   * Access key.
   *
   * @var string
   */
  protected $aws_access_key;

  /**
   * Secret key.
   *
   * @var string
   */
  protected $aws_secret_key;

  /**
   * User ID.
   *
   * @var string
   */
  protected $user_id;

  /**
   * Image upload URL.
   *
   * @var string
   */
  protected $image_upload_url;

  /**
   * Image upload URL.
   *
   * @var string
   */
  protected $image_register_url;

  /**
   * X.509 Certificate.
   *
   * @var string
   */
  protected $certificate;

  /**
   * The time that the AWS cloud information was created.
   *
   * @var int
   */
  protected $created;

  /**
   * The time that the AWS cloud information was changed.
   *
   * @var int
   */
  protected $changed;

  /**
   * The comment language code.
   *
   * @var string
   */
  protected $langcode;

  /**
   * The Cloud Context.
   */
  public function cloud_context() {
    return $this->id();
  }

  /**
   * The Cloud Type.
   */
  public function cloud_type() {
    return $this->cloud_type;
  }

  /**
   * Description.
   */
  public function description() {
    return $this->description;
  }

  /**
   * API version.
   */
  public function api_version() {
    return $this->api_version;
  }

  /**
   * API Endpoint URI.
   */
  public function endpoint() {
    return $this->endpoint;
  }

  /**
   * API Endpoint URI.
   */
  public function region() {
    return $this->region;
  }

  /**
   * Access Key.
   */
  public function aws_access_key() {
    return $this->aws_access_key;
  }

  /**
   * Secret Key.
   */
  public function aws_secret_key() {
    return $this->aws_secret_key;
  }

  /**
   * User ID.
   */
  public function user_id() {
    return $this->user_id;
  }

  /**
   * Image Upload URL.
   */
  public function image_upload_url() {
    return $this->image_upload_url;
  }

  /**
   * Image Register URL.
   */
  public function image_register_url() {
    return $this->image_register_url;
  }

  /**
   * X.509 Certificate.
   */
  public function certificate() {
    return $this->certificate;
  }

  /**
   * The time that the AWS cloud information was created.
   */
  public function created() {
    // This is necessary.
    return $this->created;
  }

  /**
   * The time that the AWS cloud information was changed.
   */
  public function changed() {
    // This is necessary.
    return $this->changed;
  }

  /**
   * The comment language code.
   */
  public function langcode() {
    return $this->langcode;
  }

  public function entity_bundle() {
    return $this->entity_bundle;
  }
}
