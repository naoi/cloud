<?php

// Updated by yas 2016/10/10
// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/23
// Updated by yas 2016/05/21
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/17
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Created by yas 2016/04/21.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\aws_cloud\Aws\Ec2\VolumeInterface;
use Drupal\cloud\Entity\CloudContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Volume entity.
 *
 * @ingroup aws_cloud
 *
 * @ContentEntityType(
 *   id = "aws_cloud_volume",
 *   label = @Translation("AWS Cloud Volume"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder"             ,
 *     "list_builder" = "Drupal\aws_cloud\Controller\Ec2\VolumeListBuilder",
 *     "views_data"   = "Drupal\aws_cloud\Entity\Ec2\VolumeViewsData"      ,
 *
 *     "form" = {
 *       "default"    = "Drupal\aws_cloud\Form\Ec2\VolumeEditForm"  ,
 *       "add"        = "Drupal\aws_cloud\Form\Ec2\VolumeCreateForm",
 *       "edit"       = "Drupal\aws_cloud\Form\Ec2\VolumeEditForm"  ,
 *       "delete"     = "Drupal\aws_cloud\Form\Ec2\VolumeDeleteForm",
 *     },
 *     "access"       = "Drupal\aws_cloud\Controller\Ec2\VolumeAccessControlHandler",
 *   },
 *   base_table = "aws_cloud_volume",
 *   admin_permission = "administer aws cloud volume",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id",
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/clouds/aws_cloud/{cloud_context}/volume/{aws_cloud_volume}"  ,
 *     "edit-form"   = "/clouds/aws_cloud/{cloud_context}/volume/{aws_cloud_volume}/edit"  ,
 *     "delete-form" = "/clouds/aws_cloud/{cloud_context}/volume/{aws_cloud_volume}/delete",
 *     "collection"  = "/clouds/aws_cloud/{cloud_context}/volume"
 *   },
 *   field_ui_base_route = "aws_cloud_volume.settings"
 * )
 */
class Volume extends CloudContentEntityBase implements VolumeInterface {

  /**
   * {@inheritdoc}
   */
  public function volume_id() {
    return $this->get('volume_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVolumeId(string $volume_id = '') {
    return $this->set('volume_id', $volume_id);
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
  public function state() {
    return $this->get('state')->value;
  }

 /**
   * {@inheritdoc}
   */
  public function setState(string $state = '') {
    return $this->set('state', $state);
  }

  /**
   * {@inheritdoc}
   */
  public function volume_status() {
    return $this->get('volume_status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function attachment_information() {
    return $this->get('attachment_information')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function volume_type() {
    return $this->get('volume_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function product_codes() {
    return $this->get('product_codes')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function iops() {
    return $this->get('iops')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function alarm_status() {
    return $this->get('alarm_status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function snapshot_id() {
    return $this->get('snapshot_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function availability_zone() {
    return $this->get('availability_zone')->value;
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
  public function kms_key_id() {
    return $this->get('kms_key_id')->value;
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
  public function kms_key_arn() {
    return $this->get('kms_key_arn')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreated($created = 0) {
    return $this->set('created', $created);
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
      ->setDescription(t('The ID of the AwsCloudEc2Volume entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the AwsCloudEc2Volume entity.'))
      ->setReadOnly(TRUE);

    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setDescription(t('A unique machine name for the cloud provider.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the AwsCloudEc2Volume entity.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['volume_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Volume ID'))
      ->setDescription(t('The ID of the Volume'))
      ->setReadOnly(TRUE);

    $fields['size'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Size'))
      ->setDescription(t('The capacity of the Amazon EBS volume in GiB. Note that 1 GiB = 1024^3 bytes, whereas 1 GB = 1000^3 bytes.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State'))
      ->setDescription(t('The current state of the volume, for example, creating, available, in-use, deleting, or error.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['volume_status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State'))
      ->setDescription(t('The current state of the volume, for example, "Okay".'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['attachment_information'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Attachment Information'))
      ->setDescription(t('Provides the volume attachment details: the ID of the instance the volume is attached to (and its name in parentheses if applicable), the device name, and the status of the attachment, for example, attaching, attached, or detaching.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['volume_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Volume Type'))
      ->setDescription(t('Indicates whether the volume is a standard (Magnetic), gp2 (General Purpose (SSD)) or io1 (Provisioned IOPS (SSD)) volume.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['product_codes'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Product Codes'))
      ->setDescription(t('The DevPay or AWS Marketplace product codes associated with the volume, if any.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['iops'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('IOPS'))
      ->setDescription(t('The requested number of I/O operations per second that the volume can support. For Provisioned IOPS (SSD) volumes, you can provision up to 30 IOPS per GiB. For General Purpose (SSD) volumes under 1000 GiB, you get a baseline performance of 100 IOPS per GiB with bursts up to 3000 IOPS. For General Purpose (SSD) volumes above 1000 GiB you get a baseline performance of 100 per GiB up to 10000 IOPS. Learn more about EBS volume types.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['alarm_status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Alarm Status'))
      ->setDescription(t('CloudWatch alarm summary for alarms monitoring metrics for this volume.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['snapshot_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Snapshot ID'))
      ->setDescription(t('The ID of the snapshot that was used to create the volume, if applicable. A snapshot is a copy of an Amazon EBS volume at a point in time.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['availability_zone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Availability Zone'))
      ->setDescription(t('The Availability Zone in which the volume is located.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['encrypted'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Encrypted'))
      ->setDescription(t('Indicates whether the volume is encrypted.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['kms_key_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('KMS Key ID'))
      ->setDescription(t('KMS Key ID'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['kms_key_aliases'] = BaseFieldDefinition::create('string')
      ->setLabel(t('KMS Key Aliases'))
      ->setDescription(t('KMS Key Aliases'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['kms_key_arn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('KMS Key ARN'))
      ->setDescription(t('KMS Key ARN'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('Date/time the Amazon EBS volume was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['refreshed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Refreshed'))
      ->setDescription(t('The time that the entity was last refreshed.'));

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the AwsCloudEc2Volume entity author.'))
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
