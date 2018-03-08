<?php

// Updated by yas 2015/06/08
// updated by yas 2015/06/01
// updated by yas 2015/05/31
// created by yas 2015/05/30.
namespace Drupal\cloud_server_template\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\cloud_server_template\CloudServerTemplateInterface;
use Drupal\user\UserInterface;

/**
 * Defines the CloudServerTemplate entity.
 *
 * @ingroup cloud_server_template
 *
 * @ContentEntityType(
 *   id = "cloud_server_template",
 *   label = @Translation("server template"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cloud_server_template\Controller\CloudServerTemplateListBuilder",
 *     "views_data"   = "Drupal\cloud_server_template\Entity\CloudServerTemplateViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\cloud_server_template\Form\CloudServerTemplateEditForm"  ,
 *       "add"     = "Drupal\cloud_server_template\Form\CloudServerTemplateEditForm"  ,
 *       "edit"    = "Drupal\cloud_server_template\Form\CloudServerTemplateEditForm"  ,
 *       "delete"  = "Drupal\cloud_server_template\Form\CloudServerTemplateDeleteForm",
 *     },
 *     "access" = "Drupal\cloud_server_template\Controller\CloudServerTemplateAccessControlHandler",
 *   },
 *   base_table = "cloud_server_template",
 *   admin_permission = "administer cloud server templates",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id",
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/entity.cloud_server_template.canonical"  ,
 *     "edit-form"   = "/entity.cloud_server_template.edit_form"  ,
 *     "delete-form" = "/entity.cloud_server_template.delete_form",
 *     "collection"  = "/entity.cloud_server_template.collection"
 *   },
 *   field_ui_base_route = "cloud_server_template.settings"
 * )
 */
class CloudServerTemplate extends ContentEntityBase implements CloudServerTemplateInterface {

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
  public function instance_type() {
    return $this->get('instance_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function image_id() {
    return $this->get('image_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function kernel_id() {
    return $this->get('kernel_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function ramdisk_id() {
    return $this->get('ramdisk_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function group_id() {
    return $this->get('group_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function ssh_key_id() {
    return $this->get('ssh_key_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function user_data() {
    return $this->get('user_data')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function instance_count() {
    return $this->get('instance_count')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function created() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function changed() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setCloudContext($cloud_context = 'default_cloud_context') {
    $this->set('cloud_context', $cloud_context);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * The comment language code.
   */
  public function langcode() {
    return $this->get('langcode');
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the cloud server templates entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the cloud server templates entity.'))
      ->setReadOnly(TRUE);

    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setDescription(t('A unique machine name for the cloud provider.'));
    // Don't set up the default value for cloud_context here!
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the cloud server templates entity.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
    /*
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
     */
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('Cloud server template description.'));

    $fields['instance_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Instance Type'))
      ->setDescription(t('Instance Type.'))
      ->setRequired(TRUE);

    $fields['image_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Image ID'))
      ->setDescription(t('Image ID.'))
      ->setRequired(TRUE);

    $fields['kernel_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Kernel ID'))
      ->setDescription(t('Kernel ID.'));

    $fields['ramdisk_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ramdisk ID'))
      ->setDescription(t('Ramdisk ID.'));

    $fields['group_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Placement'))
      ->setDescription(t('Group ID.'));

    $fields['user_data'] = BaseFieldDefinition::create('string')
      ->setLabel(t('User Data'))
      ->setDescription(t('User Data.'));

    $fields['instance_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Count'))
      ->setDescription(t('Instance Count.'))
      ->setDefaultValue(1);

    $fields['ssh_key_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('SSH Key'))
      ->setDescription(t('SSH Key Name.'))
      ->setRequired(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the CloudServerTemplate entity author.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of CloudServerTemplate entity.'));

    return $fields;
  }

}
