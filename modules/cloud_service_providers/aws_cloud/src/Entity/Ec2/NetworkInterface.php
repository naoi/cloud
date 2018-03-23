<?php

// Updated by yas 2016/10/10
// Updated by yas 2016/06/04
// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/20
// Updated by yas 2016/05/19
// Updated by yas 2016/05/18
// Updated by yas 2016/05/17
// Updated by yas 2016/05/11
// Updated by yas 2016/05/10
// Updated by yas 2016/04/26
// Created by yas 2016/04/21.
namespace Drupal\aws_cloud\Entity\Ec2;

use Drupal\aws_cloud\Aws\Ec2\NetworkInterfaceInterface;
use Drupal\cloud\Entity\CloudContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the NetworkInterface entity.
 *
 * @ingroup aws_cloud
 *
 * @ContentEntityType(
 *   id = "aws_cloud_network_interface",
 *   label = @Translation("AWS Cloud Network Interface"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder"                       ,
 *     "list_builder" = "Drupal\aws_cloud\Controller\Ec2\NetworkInterfaceListBuilder",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data"   = "Drupal\aws_cloud\Entity\Ec2\NetworkInterfaceViewsData" ,
 *     "form" = {
 *       "default"    = "Drupal\aws_cloud\Form\Ec2\NetworkInterfaceEditForm"  ,
 *       "add"        = "Drupal\aws_cloud\Form\Ec2\NetworkInterfaceCreateForm",
 *       "edit"       = "Drupal\aws_cloud\Form\Ec2\NetworkInterfaceEditForm"  ,
 *       "delete"     = "Drupal\aws_cloud\Form\Ec2\NetworkInterfaceDeleteForm",
 *     },
 *     "access"       = "Drupal\aws_cloud\Controller\Ec2\NetworkInterfaceAccessControlHandler",
 *   },
 *   base_table = "aws_cloud_network_interface",
 *   admin_permission = "administer aws cloud network interface",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id"    = "id",
 *     "label" = "name",
 *     "uuid"  = "uuid"
 *   },
 *   links = {
 *     "canonical"   = "/clouds/aws_cloud/{cloud_context}/network_interface/{aws_cloud_network_interface}",
 *     "edit-form"   = "/clouds/aws_cloud/{cloud_context}/network_interface/{aws_cloud_network_interface}/edit",
 *     "delete-form" = "/clouds/aws_cloud/{cloud_context}/network_interface/{aws_cloud_network_interface}/delete",
 *     "collection"  = "/clouds/aws_cloud/{cloud_context}/network_interface"
 *   },
 *   field_ui_base_route = "aws_cloud_network_interface.settings"
 * )
 */
class NetworkInterface extends CloudContentEntityBase implements NetworkInterfaceInterface {

  /**
   * {@inheritdoc}
   */
  public function network_interface_id() {
    return $this->get('network_interface_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNetworkInterfaceId(string $network_interface_id) {
    return $this->set('network_interface_id', $network_interface_id);
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
  public function setVpcId(string $vpc_id = '') {
    return $this->set('vpc_id', $vpc_id);
  }

  /**
   * {@inheritdoc}
   */
  public function mac_address() {
    return $this->get('mac_address')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function security_groups() {
    return $this->get('security_groups')->value;
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
  public function setStatus(string $status = '') {
    return $this->set('status', $status);
  }

  /**
   * {@inheritdoc}
   */
  public function private_dns() {
    return $this->get('private_dns')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function primary_private_ip() {
    return $this->get('primary_private_ip')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function primary() {
    return $this->get('is_primary')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function secondary_private_ips() {
    return $this->get('secondary_private_ips')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function attachment_id() {
    return $this->get('attachment_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function attachment_owner() {
    return $this->get('attachment_owner')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function attachment_status() {
    return $this->get('attachment_status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function owner_id() {
    return $this->get('owner_id')->value;
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
  public function subnet_id() {
    return $this->get('subnet_id')->value;
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
  public function description() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function public_ips() {
    return $this->get('public_ips')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function source_dest_check() {
    return $this->get('source_dest_check')->value;
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
  public function device_index() {
    return $this->get('device_index')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function delete_on_termination() {
    return $this->get('delete_on_termination')->value;
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
  public function owner() {
    return $this->get('owner')->value;
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
  public function refreshed() {
    return $this->get('refreshed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the AwsCloudEc2NetworkInterface entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the AwsCloudEc2NetworkInterface entity.'))
      ->setReadOnly(TRUE);

    $fields['cloud_context'] = BaseFieldDefinition::create('string')
      ->setRequired(TRUE)
      ->setLabel(t('Cloud Provider Machine Name'))
      ->setDescription(t('A unique machine name for the cloud provider.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the AwsCloudEc2NetworkInterface entity.'))
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

    $fields['network_interface_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Network Interface ID'))
      ->setDescription(t('The ID of the network interface entity.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['vpc_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('VPC ID'))
      ->setDescription(t('The ID of the VPC in which the network interface is located. A VPC is an isolated portion of the AWS cloud.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status'))
      ->setDescription(t('The current status of the network interface; for example, available or in-use.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['mac_address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Mac Address'))
      ->setDescription(t("The network interface's Media Access Control (MAC) address."))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['security_groups'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Security Groups'))
      ->setDescription(t('The names of the security groups associated with the network interface. You can change the security group associated with the network interface using the Actions menu.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['private_dns'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Private DNS'))
      ->setDescription(t("The private hostname of the network interface, which resolves to the interface's private IP address."))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['primary_private_ip'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Primary Private IP'))
      ->setDescription(t('The primary private IP address of the network interface.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['is_primary'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Primary or Not'))
      ->setDescription(t('The primary private IP address or not.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['secondary_private_ips'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Secondary Private IPs'))
      ->setDescription(t('Any secondary private IP addresses associated with the network interface, if applicable.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['attachment_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Attachment ID'))
      ->setDescription(t("The ID identifying the network interface's attachment to an instance."))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['attachment_owner'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Attachment Owner'))
      ->setDescription(t('The owner of the instance, which may be expressed as an AWS account number.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['attachment_status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Attachment Status'))
      ->setDescription(t('The current attachment status of the network interface; for example, attaching, attached, or detaching.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['owner_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Owner ID'))
      ->setDescription(t('The AWS account number of the Elastic IP address owner.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['owner'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Owner ID'))
      ->setDescription(t('The AWS account number of the network interface creator.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['association_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Association ID'))
      ->setDescription(t('The ID identifying an Elastic IP address association with the network interface.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['subnet_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subnet ID'))
      ->setDescription(t('The ID of the subnet in which the network interface is located. A subnet is a range of IP addresses in a VPC.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['availability_zone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Availability Zone'))
      ->setDescription(t('The Availability Zone in which the network interface is located. Availability Zones are distinct locations within a region.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('The user-supplied description of the network interface.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['public_ips'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Public IPs'))
      ->setDescription(t('The public IP address and Elastic IP addresses associated with the network interface, if applicable. An asterisk (*) indicates that the address is associated with the primary private IP address of the network interface.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['instance_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Instance ID'))
      ->setDescription(t('The ID of the instance to which the network interface is attached, if applicable.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['device_index'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Device Index'))
      ->setDescription(t('If the network interface is attached to an instance, the interface device number to help distinguish it from other attached network interfaces. A primary network interface has a device index of 0.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['delete_on_termination'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Delete on Termination'))
      ->setDescription(t('If the network interface is attached to an instance, the termination option if the instance is deleted.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'boolean',
        'weight' => -5,
      ])
      ->setReadOnly(TRUE);

    $fields['allocation_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Allocation ID'))
      ->setDescription(t('If the network interface is attached to an instance, the termination option if the instance is deleted.'))
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
      ->setDescription(t('The user ID of the AwsCloudEc2NetworkInterface entity author.'))
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
