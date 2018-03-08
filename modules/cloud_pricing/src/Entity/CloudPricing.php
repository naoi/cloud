<?php

// Updated by yas 2015/06/08
// updated by yas 2015/06/05.
namespace Drupal\cloud_pricing\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\cloud_pricing\CloudPricingInterface;

/**
 * Defines the CloudPricing entity.
 *
 * @ConfigEntityType(
 *   id    = "cloud_pricing",
 *   label = @Translation("Cloud pricing information"),
 *   handlers = {
 *     "list_builder"  = "Drupal\cloud_pricing\Controller\CloudPricingListBuilder",
 *     "form" = {
 *       "add"         = "Drupal\cloud_pricing\Form\CloudPricingEditForm",
 *       "edit"        = "Drupal\cloud_pricing\Form\CloudPricingEditForm",
 *       "delete"      = "Drupal\cloud_pricing\Form\CloudPricingDeleteForm"
 *     }
 *   },
 *   config_prefix = "cloud",
 *   admin_permission  = "administer cloud pricing",
 *   entity_keys = {
 *     "id"            = "id",
 *     "uuid"          = "uuid",
 *     "cloud_context" = "cloud_context",
 *   },
 *   links = {
 *     "edit-form"     = "/entity.cloud_pricing.edit_form.edit",
 *     "delete-form"   = "/entity.cloud_pricing.delete_form",
 *     "collection"    = "/entity.cloud_pricing.collection"
 *   }
 * )
 */
class CloudPricing extends ConfigEntityBase implements CloudPricingInterface {

  /**
   * The Cloud Pricing Cloud Context.
   *
   * @var string
   */
  protected $cloud_context = 'default_cloud_context';

  /**
   * The Cloud Pricing Instance Type.
   *
   * @var string
   */
  protected $instance_type = '';

  /**
   * The Cloud Pricing Linux Usage.
   *
   * @var float
   */
  protected $linux_usage = 0.00;

  /**
   * The Cloud Pricing Windows Usage.
   *
   * @var float
   */
  protected $windows_usage = 0.00;

  /**
   * The Cloud Pricing Description.
   *
   * @var string
   */
  protected $description = '';

  /**
   * The time that the pricing information was created.
   *
   * @var int
   */
  protected $created;

  /**
   * The time that the pricing information was changed.
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
   * The Cloud Pricing Cloud Context.
   */
  public function cloud_context() {
    return $this->get('cloud_context');
  }

  /**
   * The Cloud Pricing Cloud Context.
   */
  public function instance_type() {
    return $this->get('instance_type');
  }

  /**
   * The Cloud Pricing Linux Usage.
   */
  public function linux_usage() {
    return $this->get('linux_usage');
  }

  /**
   * The Cloud Pricing Windows Usage.
   */
  public function windows_usage() {
    return $this->get('windows_usage');
  }

  /**
   * The Cloud Pricing Description.
   */
  public function description() {
    return $this->get('description');

  }

  /**
   * The time that the pricing information was created.
   */
  public function created() {
    return $this->get('created');
  }

  /**
   * The time that the pricing information was changed.
   */
  public function changed() {
    return $this->get('changed');
  }

  /**
   * The comment language code.
   */
  public function langcode() {
    return $this->get('langcode');
  }

}
