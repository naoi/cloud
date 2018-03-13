<?php

namespace Drupal\cloud_server_template\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Cloud Server Template type entity.
 *
 * @ConfigEntityType(
 *   id = "cloud_server_template_type",
 *   label = @Translation("Cloud Server Template type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cloud_server_template\CloudServerTemplateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\cloud_server_template\Form\CloudServerTemplateTypeForm",
 *       "edit" = "Drupal\cloud_server_template\Form\CloudServerTemplateTypeForm",
 *       "delete" = "Drupal\cloud_server_template\Form\CloudServerTemplateTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cloud_server_template\Routing\CloudServerTemplateTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "cloud_server_template_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "cloud_server_template",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/cloud_server_template_type/{cloud_server_template_type}",
 *     "add-form" = "/admin/structure/cloud_server_template_type/add",
 *     "edit-form" = "/admin/structure/cloud_server_template_type/{cloud_server_template_type}/edit",
 *     "delete-form" = "/admin/structure/cloud_server_template_type/{cloud_server_template_type}/delete",
 *     "collection" = "/admin/structure/cloud_server_template_type"
 *   }
 * )
 */
class CloudServerTemplateType extends ConfigEntityBundleBase implements CloudServerTemplateTypeInterface {

  /**
   * The Cloud Server Template type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Cloud Server Template type label.
   *
   * @var string
   */
  protected $label;

}
