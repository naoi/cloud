<?php

// Created by yas 2016/05/25.
namespace Drupal\cloud_cluster\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\cloud_cluster\CloudClusterInterface;
use Drupal\user\UserInterface;

/**
 * Defines the CloudCluster entity.
 *
 * @ingroup cloud_cluster
 *
 * @ContentEntityType(
 *   id = "cloud_cluster",
 *   label = @Translation("Cloud Cluster entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cloud_cluster\Controller\CloudClusterListController",
 *     "views_data"   = "Drupal\cloud_cluster\Entity\CloudClusterViewsData",
 *
 *     "form" = {
 *       "default"    = "Drupal\cloud_cluster\Form\CloudClusterEditForm"  ,
 *       "add"        = "Drupal\cloud_cluster\Form\CloudClusterEditForm"  ,
 *       "edit"       = "Drupal\cloud_cluster\Form\CloudClusterEditForm"  ,
 *       "delete"     = "Drupal\cloud_cluster\Form\CloudClusterDeleteForm",
 *     },
 *     "access"       = "Drupal\cloud_cluster\Controller\CloudClusterAccessControlHandler",
 *   },
 *   base_table = "cloud_cluster",
 *   admin_permission = "administer cloud cluster",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id"  ,
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/entity.cloud_cluster.canonical"  ,
 *     "edit-form"   = "/entity.cloud_cluster.edit_form"  ,
 *     "delete-form" = "/entity.cloud_cluster.delete_form",
 *     "collection"  = "/entity.cloud_cluster.collection"
 *   },
 *   field_ui_base_route = "cloud_cluster.settings"
 * )
 */
class CloudCluster extends ContentEntityBase implements CloudClusterInterface {

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
  public function description() {
    return $this->get('description')->value;
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
      ->setDescription(t('The ID of the Cloud Cluster entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Cloud Cluster entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the CloudCluster entity author.'))
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the cloud cluster entity.'))
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

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of CloudCluster entity.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
