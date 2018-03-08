<?php

// Updated by yas 2016/10/10
// Updated by yas 2016/06/04
// Updated by yas 2016/05/30
// Updated by yas 2016/05/28
// updated by yas 2016/05/27
// updated by yas 2016/05/25
// updated by yas 2016/05/23
// updated by yas 2016/05/21
// updated by yas 2016/05/19
// updated by yas 2016/05/18
// updated by yas 2016/05/17
// updated by yas 2016/05/11
// updated by yas 2016/05/10
// created by yas 2016/04/28.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\aws_cloud\Aws\Ec2\SnapshotInterface;

/**
 * Defines the Snapshot entity.
 *
 * @ingroup aws_cloud
 *
 * @ContentEntityType(
 *   id = "aws_cloud_snapshot",
 *   label = @Translation("AWS Cloud Snapshot"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder"               ,
 *     "list_builder" = "Drupal\aws_cloud\Controller\Ec2\SnapshotListBuilder",
 *     "views_data"   = "Drupal\aws_cloud\Entity\Ec2\SnapshotViewsData"      ,
 *
 *     "form" = {
 *       "default"    = "Drupal\aws_cloud\Form\Ec2\SnapshotEditForm"  ,
 *       "add"        = "Drupal\aws_cloud\Form\Ec2\SnapshotCreateForm",
 *       "edit"       = "Drupal\aws_cloud\Form\Ec2\SnapshotEditForm"  ,
 *       "delete"     = "Drupal\aws_cloud\Form\Ec2\SnapshotDeleteForm",
 *     },
 *     "access"       = "Drupal\aws_cloud\Controller\Ec2\SnapshotAccessControlHandler",
 *   },
 *   base_table = "aws_cloud_snapshot",
 *   admin_permission = "administer aws cloud snapshot",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id"  ,
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/clouds/aws_cloud/{cloud_context}/snapshot/{aws_cloud_snapshot}"  ,
 *     "edit-form"   = "/clouds/aws_cloud/{cloud_context}/snapshot/{aws_cloud_snapshot}/edit"  ,
 *     "delete-form" = "/clouds/aws_cloud/{cloud_context}/snapshot/{aws_cloud_snapshot}/delete",
 *     "collection"  = "/clouds/aws_cloud/{cloud_context}/snapshot"
 *   },
 *   field_ui_base_route = "aws_cloud.snapshot.settings"
 * )
 */
class Snapshot extends EC2ContentEntityBase implements SnapshotInterface {

  /**
   * {@inheritdoc}
   */
  public function snapshot_id() {
    return $this->get('snapshot_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSnapshotId(string $snapshot_id = '') {
    return $this->set('snapshot_id', $snapshot_id);
  }

  /**
   * {@inheritdoc}
   */
  public function size() {
    return $this->get('size')->value;
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
  public function status() {
    return $this->get('status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStatus($status = 'unknown') {
    return $this->set('status', $status);
  }

  /**
   * {@inheritdoc}
   */
  public function volume_id() {
    return $this->get('volume_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function owner_id() {
    return $this->get('owner')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function owner_aliases() {
    return $this->get('owner_aliases')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function encrypted() {
    return $this->get('encrypted')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEncrypted($encrypted = FALSE) {
    return $this->set('encrypted', $encrypted);
  }

  /**
   * {@inheritdoc}
   */
  public function kms_key_id() {
    return $this->get('kms_key_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function state_message() {
    return $this->get('state_message')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function started() {
    return $this->get('started')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStarted($started = '') {
    return $this->set('started', $started);
  }

  /**
   * {@inheritdoc}
   */
  public function progress() {
    return $this->get('progress')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function kms_key_aliases() {
    return $this->get('kms_key_aliases')->value;
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
  public function getOwnerId() {
    return $this->get('owner_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($owner_id) {
    return $this->set('owner_id', $owner_id);
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
  public function setOwner(UserInterface $account) {
    return $this->set('user_id', $account->id());
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the AwsCloudEc2Snapshot entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the AwsCloudEc2Snapshot entity.'))
      ->setReadOnly(TRUE);

    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setDescription(t('A unique machine name for the cloud provider.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the AwsCloudEc2ElasticIp entity.'))
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
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['snapshot_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Snapshot ID'))
      ->setDescription(t('The Snapshot ID.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['size'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Size'))
      ->setDescription(t('The size of the snapshot.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('Description of source snapshot.'));

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status'))
      ->setDescription(t('The current state of the snapshot; for example, pending, completed, or error.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['volume_id'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Volume ID'))
      ->setDescription(t('The volume ID from which the snapshot was created. A snapshot is a copy of an Amazon EBS volume at a point in time.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['started'] = BaseFieldDefinition::create('integer')
      ->setRequired(TRUE)
      ->setLabel(t('Started'))
      ->setDescription(t('The date and time when the snapshot started.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['owner'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('Owner'))
      ->setDescription(t('The AWS account ID of the snapshot owner.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['progress'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Progress'))
      ->setDescription(t('The portion (percentage) of the snapshot that has been created.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['capacity'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Capacity'))
      ->setDescription(t('The size of the Amazon EBS volume from which the snapshot was created, in GiB.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['encrypted'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Encrypted'))
      ->setDescription(t('Indicates whether the snapshot is encrypted.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['product_codes'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Product Code'))
      ->setDescription(t('AWS Marketplace product codes associated with the snapshot, if any.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['kms_key_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('KMS Key ID'))
      ->setDescription(t('KMS Key ID.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['kms_key_aliases'] = BaseFieldDefinition::create('string')
      ->setLabel(t('KMS Key Aliases'))
      ->setDescription(t('KMS Key Aliases.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['kms_key_arn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('KMS Key ARN'))
      ->setDescription(t('KMS Key ARN.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['refreshed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Refreshed'))
      ->setDescription(t('The time that the entity was last refreshed.'));

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the AwsCloudEc2Snapshot entity author.'))
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
