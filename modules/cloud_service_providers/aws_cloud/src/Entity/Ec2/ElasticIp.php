<?php

// Updated by yas 2016/10/10
// Updated by yas 2016/07/05
// Updated by yas 2016/06/03
// Updated by yas 2016/05/30
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/23
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/17
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Created by yas 2016/04/21.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\aws_cloud\Aws\Ec2\ElasticIpInterface;

/**
 * Defines the ElasticIp entity.
 *
 * @ingroup aws_cloud
 *
 * @ContentEntityType(
 *   id = "aws_cloud_elastic_ip",
 *   label = @Translation("AWS Cloud Elastic IP"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder"                ,
 *     "list_builder" = "Drupal\aws_cloud\Controller\Ec2\ElasticIpListBuilder",
 *     "views_data"   = "Drupal\aws_cloud\Entity\Ec2\ElasticIpViewsData"      ,
 *     "form" = {
 *       "default"    = "Drupal\aws_cloud\Form\Ec2\ElasticIpEditForm"  ,
 *       "add"        = "Drupal\aws_cloud\Form\Ec2\ElasticIpCreateForm",
 *       "edit"       = "Drupal\aws_cloud\Form\Ec2\ElasticIpEditForm"  ,
 *       "delete"     = "Drupal\aws_cloud\Form\Ec2\ElasticIpDeleteForm",
 *     },
 *     "access"       = "Drupal\aws_cloud\Controller\Ec2\ElasticIpAccessControlHandler",
 *   },
 *   base_table = "aws_cloud_elastic_ip",
 *   admin_permission = "administer aws cloud elastic ip",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id"  ,
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/clouds/aws_cloud/{cloud_context}/elastic_ip/{aws_cloud_elastic_ip}"  ,
 *     "edit-form"   = "/clouds/aws_cloud/{cloud_context}/elastic_ip/{aws_cloud_elastic_ip}/edit"  ,
 *     "delete-form" = "/clouds/aws_cloud/{cloud_context}/elastic_ip/{aws_cloud_elastic_ip}/delete",
 *     "collection"  = "/clouds/aws_cloud/{cloud_context}/elastic_ip"
 *   },
 *   field_ui_base_route = "aws_cloud_elastic_ip.settings"
 * )
 */
class ElasticIp extends EC2ContentEntityBase implements ElasticIpInterface {

  /**
   * {@inheritdoc}
   */
  public function public_ip() {
    return $this->get('public_ip')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublicIp($public_ip = '') {
    return $this->set('public_ip', $public_ip);
  }

  /**
   * {@inheritdoc}
   */
  public function domain() {
    return $this->get('domain')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function association_id() {
    return $this->get('association_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function allocation_id() {
    return $this->get('allocation_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAllocationId($allocation_id = '') {
    return $this->set('allocation_id', $allocation_id);
  }

  /**
   * {@inheritdoc}
   */
  public function instance_id() {
    return $this->get('instance_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function scope() {
    return $this->get('scope')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function network_interface_id() {
    return $this->get('network_interface_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function private_ip_address() {
    return $this->get('private_ip_address')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function network_interface_owner() {
    return $this->get('network_interface_owner')->value;
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
  public function getOwner() {
    return $this->get('user_id')->entity;
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
  public function setOwner(UserInterface $account) {
    return $this->set('user_id', $account->id());
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the AwsCloudEc2ElasticIp entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the AwsCloudEc2ElasticIp entity.'))
      ->setReadOnly(TRUE);

    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setDescription(t('A unique machine name for the cloud provider.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The Elastic IP address name.'))
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
    /*
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
     */
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['public_ip'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Elastic IP'))
      ->setDescription(t('The Elastic IP Address.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 15,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['instance_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Instance ID'))
      ->setDescription(t('The instance the Elastic IP address is associated with, if applicable.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['domain'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Domain'))
      ->setDescription(t('The instance the Elastic IP address is associated with, if applicable.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['scope'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Scope'))
      ->setDescription(t('Indicates if the Elastic IP address is for use in EC2-Classic (standard) or in a VPC (vpc).'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['network_interface_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Network Interface ID'))
      ->setDescription(t('For instances in a VPC, indicates the ID of the network interface to which the Elastic IP is associated.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['private_ip_address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Private IP Address'))
      ->setDescription(t('The private IP address of the network interface to which the Elastic IP address is associated.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['network_interface_owner'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Network Interface Owner'))
      ->setDescription(t('The AWS account number of the network interface owner.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['allocation_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Allocation ID'))
      ->setDescription(t('The allocation ID of the Elastic IP address. Only applicable to Elastic IP addresses used in a VPC.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['association_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Association ID'))
      ->setDescription(t('The ID representing the association of the address with an instance in a VPC.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

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
      ->setDescription(t('The user ID of the AwsCloudEc2ElasticIp entity author.'))
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
