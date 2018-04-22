<?php

// Updated by yas 2016/10/10
// Updated by yas 2016/06/03
// Updated by yas 2016/05/30
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// udpated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/17
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Created by yas 2016/04/21.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\aws_cloud\Aws\Ec2\KeyPairInterface;
use Drupal\cloud\Entity\CloudContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the KeyPair entity.
 *
 * @ingroup aws_cloud
 *
 * @ContentEntityType(
 *   id = "aws_cloud_key_pair",
 *   label = @Translation("AWS Cloud Key Pair"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder"              ,
 *     "list_builder" = "Drupal\aws_cloud\Controller\Ec2\KeyPairListBuilder",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data"   = "Drupal\aws_cloud\Entity\Ec2\KeyPairViewsData"      ,
 *     "form" = {
 *       "default"    = "Drupal\aws_cloud\Form\Ec2\KeyPairEditForm"  ,
 *       "add"        = "Drupal\aws_cloud\Form\Ec2\KeyPairCreateForm",
 *       "edit"       = "Drupal\aws_cloud\Form\Ec2\KeyPairEditForm"  ,
 *       "delete"     = "Drupal\aws_cloud\Form\Ec2\KeyPairDeleteForm",
 *     },
 *     "access"       = "Drupal\aws_cloud\Controller\Ec2\KeyPairAccessControlHandler",
 *   },
 *   base_table = "aws_cloud_key_pair",
 *   admin_permission = "administer aws cloud key pair",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id",
 *     "label" = "key_pair_name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/clouds/aws_cloud/{cloud_context}/key_pair/{aws_cloud_key_pair}"  ,
 *     "edit-form"   = "/clouds/aws_cloud/{cloud_context}/key_pair/{aws_cloud_key_pair}/edit"  ,
 *     "delete-form" = "/clouds/aws_cloud/{cloud_context}/key_pair/{aws_cloud_key_pair}/delete",
 *     "collection"  = "/clouds/aws_cloud/{cloud_context}/key_pair"
 *   },
 *   field_ui_base_route = "aws_cloud_key_pair.settings"
 * )
 */
class KeyPair extends CloudContentEntityBase implements KeyPairInterface {

  /**
   * {@inheritdoc}
   */
  public function key_pair_name() {
    return $this->get('key_pair_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function key_fingerprint() {
    return $this->get('key_fingerprint')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setKeyFingerprint($key_fingerprint = '') {
    return $this->set('key_fingerprint', $key_fingerprint);
  }

  /**
   * {@inheritdoc}
   */
  public function key_material() {
    return $this->get('key_material')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setKeyMaterial($key_material = '') {
    return $this->set('key_material', $key_material);
  }

  /**
   * {@inheritdoc}
   */
  public function refreshed() {
    return $this->get('refreshed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRefreshed($time) {
    return $this->set('refreshed', $time);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the AwsCloudEc2KeyPair entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the AwsCloudEc2KeyPair entity.'))
      ->setReadOnly(TRUE);

    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setDescription(t('A unique machine name for the cloud provider.'));

    $fields['key_pair_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Key Pair Name'))
      ->setDescription(t('The user-supplied key pair name, which is used to connect to an instance.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['key_fingerprint'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Fingerprint'))
      ->setDescription(t('The unique fingerprint of the key pair, which can be used to confirm its authenticity.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['key_material'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Key Material'))
      ->setDescription(t('The key pair material (a private key)'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 5120,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['refreshed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Refreshed'))
      ->setDescription(t('The time that the entity was last refreshed.'));

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the AwsCloudEc2KeyPair entity author.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
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

    return $fields;
  }

}
