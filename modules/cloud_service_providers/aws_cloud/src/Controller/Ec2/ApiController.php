<?php

namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\aws_cloud\Aws\Config\ConfigInterface;
use Drupal\aws_cloud\Aws\Ec2\ApiControllerInterface;
use Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Controller responsible for "update" urls.  This class is mainly responsible for
 * updating the aws entities from urls.
 */
class ApiController extends ControllerBase implements ApiControllerInterface {

  /**
   * The Aws Ec2 Service
   * @var \Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
   */
  private $awsEc2Service;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * ApiController constructor.
   * @param \Drupal\aws_cloud\Service\AwsEc2ServiceInterface $aws_ec2_service
   *  Object for interfacing with AWS Api
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *  Messanger Object
   */
  public function __construct(AwsEc2ServiceInterface $aws_ec2_service, Messenger $messenger) {
    $this->awsEc2Service = $aws_ec2_service;
    $this->messenger = $messenger;
  }

  /**
   * Dependency Injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('aws_cloud.ec2'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function updateInstanceList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateInstances();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated instances.'));
    }
    else {
      $this->messageUser($this->t('Unable to update instances.'));
    }

    return $this->redirect('entity.aws_cloud_instance.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateImageList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateImages();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated images.'));
    }
    else {
      $this->messageUser($this->t('Unable to update images.'));
    }
    return $this->redirect('view.images.page_1', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateSecurityGroupList(ConfigInterface $cloud_context) {
    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateSecurityGroups();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated security groups.'));
    }
    else {
      $this->messageUser($this->t('Unable to update security groups.'));
    }
    return $this->redirect('entity.aws_cloud_security_group.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateNetworkInterfaceList(ConfigInterface $cloud_context) {
    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateNetworkInterfaces();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated network interfaces.'));
    }
    else {
      $this->messageUser($this->t('Unable to update network interfaces.'));
    }

    return $this->redirect('entity.aws_cloud_network_interface.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateElasticIpList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateElasticIp();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated elastic ips.'));
    }
    else {
      $this->messageUser($this->t('Unable to update elastic ips.'));
    }

    return $this->redirect('entity.aws_cloud_elastic_ip.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateKeyPairList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateKeyPairs();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated key pairs.'));
    }
    else {
      $this->messageUser($this->t('Unable to update key pairs.'));
    }

    return $this->redirect('entity.aws_cloud_key_pair.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateVolumeList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateVolumes();

    if ($updated != FALSE) {
      $this->messageUser($this->t('Updated volumes.'));
    }
    else {
      $this->messageUser($this->t('Unable to update volumes.'));
    }

    return $this->redirect('entity.aws_cloud_volume.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateSnapshotList(ConfigInterface $cloud_context) {

    $this->awsEc2Service->setCloudContext($cloud_context->id());
    $updated = $this->awsEc2Service->updateSnapshots();

    if ($updated !== FALSE) {
      $this->messageUser($this->t('Updated snapshots.'));
    }
    else {
      $this->messageUser($this->t('Unable to update snapshots.'), 'error');
    }

    return $this->redirect('entity.aws_cloud_snapshot.collection', [
      'cloud_context' => $cloud_context->id(),
    ]);
  }

  /**
   * Helper method to add messages for the end user
   * @param string $message
   *  The message
   * @param string $type
   *  The message type: error or message
   */
  private function messageUser($message, $type = 'message') {
    switch ($type) {
      case 'error':
        $this->messenger->addError($message);
        break;
      case 'message':
        $this->messenger->addMessage($message);
      default:
        break;
    }
  }

}
