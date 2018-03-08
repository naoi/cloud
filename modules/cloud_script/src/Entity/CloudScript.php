<?php

// Changed by yas 2015/06/08
// created by yas 2015/06/03.
namespace Drupal\cloud_script\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\cloud_script\CloudScriptInterface;
use Drupal\user\UserInterface;

/**
 * Defines the CloudScript entity.
 *
 * @ingroup cloud_script
 *
 * @ContentEntityType(
 *   id = "cloud_script",
 *   label = @Translation("scripts"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cloud_script\Controller\CloudScriptListBuilder",
 *     "views_data"   = "Drupal\cloud_script\Entity\CloudScriptViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\cloud_script\Form\CloudScriptEditForm"  ,
 *       "add"     = "Drupal\cloud_script\Form\CloudScriptEditForm"  ,
 *       "edit"    = "Drupal\cloud_script\Form\CloudScriptEditForm"  ,
 *       "delete"  = "Drupal\cloud_script\Form\CloudScriptDeleteForm",
 *     },
 *     "access" = "Drupal\cloud_script\Controller\CloudScriptAccessControlHandler",
 *   },
 *   base_table = "cloud_script",
 *   admin_permission = "administer cloud script",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id",
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/entity.cloud_script.canonical"  ,
 *     "edit-form"   = "/entity.cloud_script.edit_form"  ,
 *     "delete-form" = "/entity.cloud_script.delete_form",
 *     "collection"  = "/entity.cloud_script.collection"
 *   },
 *   field_ui_base_route = "cloud_script.settings"
 * )
 */
class CloudScript extends ContentEntityBase implements CloudScriptInterface {

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
  /*
  public function cloud_context() {
  return $this->get('cloud_context')->value;
  }
   */

  /**
   * {@inheritdoc}
   */
  public function type() {
    return $this->get('type')->value;
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
  public function input_parameters() {
    return $this->get('input_parameters')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function script() {
    return $this->get('script')->value;
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
   * The comment language code.
   */
  public function langcode() {
    return $this->get('langcode');
  }

  /**
   * {@inheritdoc}
   */
  /*
  public function setCloudContext($cloud_context) {
  $this->set('cloud_context', $cloud_context);
  return $this;
  }
   */

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
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Script entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the CloudScript entity.'))
      ->setReadOnly(TRUE);

    /*
    $fields['cloud_context'] = BaseFieldDefinition::create('string')
    ->setRequired(TRUE)
    ->setLabel(t('Cloud Provider Machine Name'))
    ->setDescription(t('A unique machine name for the cloud provider.'));
     */

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the CloudScript entity.'))
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
      ->setDescription(t('Script Description.'));

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('Type.'))
      ->setRequired(TRUE);

    $fields['input_parameters'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Input Parameters'))
      ->setDescription(t('Input Parameters.'))
      ->setRequired(TRUE);

    $fields['script'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Script'))
      ->setDescription(t('Script.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the CloudScript entity author.'))
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
      ->setDescription(t('The language code of CloudScript entity.'));

    return $fields;
  }

}
