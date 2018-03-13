<?php

namespace Drupal\cloud_server_template\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Cloud Server Template entity.
 *
 * @ingroup cloud_server_template
 *
 * @ContentEntityType(
 *   id = "cloud_server_template",
 *   label = @Translation("Cloud Server Template"),
 *   bundle_label = @Translation("Cloud Server Template type"),
 *   handlers = {
 *     "storage" = "Drupal\cloud_server_template\CloudServerTemplateStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cloud_server_template\Controller\CloudServerTemplateListBuilder",
 *     "views_data" = "Drupal\cloud_server_template\Entity\CloudServerTemplateViewsData",
 *     "translation" = "Drupal\cloud_server_template\CloudServerTemplateTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\cloud_server_template\Form\CloudServerTemplateForm",
 *       "add" = "Drupal\cloud_server_template\Form\CloudServerTemplateForm",
 *       "edit" = "Drupal\cloud_server_template\Form\CloudServerTemplateForm",
 *       "delete" = "Drupal\cloud_server_template\Form\CloudServerTemplateDeleteForm",
 *     },
 *     "access" = "Drupal\cloud_server_template\Controller\CloudServerTemplateAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\cloud_server_template\Routing\CloudServerTemplateHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "cloud_server_template",
 *   data_table = "cloud_server_template_field_data",
 *   revision_table = "cloud_server_template_revision",
 *   revision_data_table = "cloud_server_template_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer cloud server template entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/clouds/design/server_template/{cloud_server_template}",
 *     "add-form" = "/clouds/design/server_template/add/{cloud_context}/{cloud_server_template_type}",
 *     "edit-form" = "/clouds/design/server_template/{cloud_server_template}/edit",
 *     "delete-form" = "/clouds/design/server_template/{cloud_server_template}/delete",
 *     "version-history" = "/clouds/design/server_template/{cloud_server_template}/revisions",
 *     "revision" = "/clouds/design/server_template/{cloud_server_template}/revisions/{cloud_server_template_revision}/view",
 *     "revision_revert" = "/clouds/design/server_template/{cloud_server_template}/revisions/{cloud_server_template_revision}/revert",
 *     "revision_delete" = "/clouds/design/server_template/{cloud_server_template}/revisions/{cloud_server_template_revision}/delete",
 *     "translation_revert" = "/clouds/design/server_template/{cloud_server_template}/revisions/{cloud_server_template_revision}/revert/{langcode}",
 *     "collection" = "/clouds/design/server_template",
 *   },
 *   bundle_entity_type = "cloud_server_template_type",
 *   field_ui_base_route = "entity.cloud_server_template_type.edit_form"
 * )
 */
class CloudServerTemplate extends RevisionableContentEntityBase implements CloudServerTemplateInterface {

  use EntityChangedTrait;

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
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    // add in cloud context
    $uri_route_parameters['cloud_context'] = $this->cloud_context();

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the cloud_server_template owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
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
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  // TODO: these have to come out somehow or get abstracted

  /**
   * {@inheritdoc}
   */
  public function cloud_context() {
    return $this->get('cloud_context')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCloudContext($cloud_context) {
    $this->set('cloud_context', $cloud_context);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Server Template.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 11,
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
      ->setDescription(t('The name of the server template.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    # TODO: make this an entity reference to config entity?  For now, leave as string
    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setRequired(TRUE)
      ->setDescription(t('A unique machine name for the cloud provider.'))
      ->setRevisionable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ]);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Cloud Server Template is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 100,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
