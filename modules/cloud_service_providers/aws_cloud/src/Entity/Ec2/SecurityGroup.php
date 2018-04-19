<?php

// Updated by yas 2016/10/10
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
// created by yas 2016/04/21.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\aws_cloud\Aws\Ec2\SecurityGroupInterface;
use Drupal\cloud\Entity\CloudContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Security Group entity.
 *
 * @ingroup aws_cloud
 *
 * @ContentEntityType(
 *   id = "aws_cloud_security_group",
 *   label = @Translation("AWS Cloud Security Group"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\aws_cloud\Controller\Ec2\SecurityGroupListBuilder",
 *     "views_data"   = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default"    = "Drupal\aws_cloud\Form\Ec2\SecurityGroupEditForm",
 *       "add"        = "Drupal\aws_cloud\Form\Ec2\SecurityGroupCreateForm",
 *       "edit"       = "Drupal\aws_cloud\Form\Ec2\SecurityGroupEditForm",
 *       "delete"     = "Drupal\aws_cloud\Form\Ec2\SecurityGroupDeleteForm",
 *     },
 *     "access"       = "Drupal\aws_cloud\Controller\Ec2\SecurityGroupAccessControlHandler",
 *   },
 *   base_table = "aws_cloud_security_group",
 *   admin_permission = "administer aws cloud security group",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id",
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/clouds/aws_cloud/{cloud_context}/security_group/{aws_cloud_security_group}",
 *     "edit-form"   = "/clouds/aws_cloud/{cloud_context}/security_group/{aws_cloud_security_group}/edit",
 *     "delete-form" = "/clouds/aws_cloud/{cloud_context}/security_group/{aws_cloud_security_group}/delete",
 *     "collection"  = "/clouds/aws_cloud/{cloud_context}/security_group"
 *   },
 *   field_ui_base_route = "aws_cloud_security_group.settings"
 * )
 */
class SecurityGroup extends CloudContentEntityBase implements SecurityGroupInterface {

  /**
   * {@inheritdoc}
   */
  public function group_id() {
    return $this->get('group_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setGroupId($group_id = '') {
    return $this->set('group_id', $group_id);
  }

  /**
   * {@inheritdoc}
   */
  public function group_name() {
    return $this->get('group_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function group_description() {
    return $this->get('group_description')->value;
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
  public function vpc_id() {
    return $this->get('vpc_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function owner() {
    return $this->get('owner')->value;
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
      ->setDescription(t('The ID of the AwsCloudEc2SecurityGroup entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the AwsCloudEc2SecurityGroup entity.'))
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

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('Description of security group.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ]);

    $fields['group_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Group ID'))
      ->setDescription(t('The ID of your security group.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['group_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Group Name'))
      ->setDescription(t('The name given to your security group.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['group_description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Group Description'))
      ->setDescription(t('The description of your security group.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['vpc_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('VPC ID'))
      ->setDescription(t('The ID of the virtual private cloud (VPC) the security group belongs to, if applicable. A VPC is an isolated portion of the AWS cloud.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['refreshed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Refreshed'))
      ->setDescription(t('The time that the entity was last refreshed.'));

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the AwsCloudEc2SecurityGroup entity author.'))
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
