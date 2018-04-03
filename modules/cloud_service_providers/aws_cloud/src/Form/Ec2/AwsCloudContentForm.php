<?php

namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
use Drupal\cloud\Form\CloudContentForm;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AwsCloudContentForm extends CloudContentForm{

  /**
   * @var \Drupal\aws_cloud\Service\AwsEc2ServiceInterface;
   */
  protected $awsEc2Service;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * AwsDeleteForm constructor.
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   * @param \Drupal\Core\Entity\EntityManagerInterface $manager
   * @param \Drupal\aws_cloud\Form\Ec2\AwsEc2ServiceInterface $aws_ec2_service
   * @param \Drupal\Core\Messenger\Messenger $messenger
   */
  public function __construct(QueryFactory $query_factory, EntityManagerInterface $manager, AwsEc2ServiceInterface $aws_ec2_service, Messenger $messenger) {
    parent::__construct($query_factory, $manager);
    $this->awsEc2Service = $aws_ec2_service;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
      $container->get('entity.manager'),
      $container->get('aws_cloud.ec2'),
      $container->get('messenger')
    );
  }

}