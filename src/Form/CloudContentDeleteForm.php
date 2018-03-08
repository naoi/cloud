<?php

// Updated by yas 2016/05/30
// Updated by yas 2016/05/29
// created by yas 2016/05/21.
namespace Drupal\cloud\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for the Cloud entity delete confirm forms.
 *
 * @ingroup cloud
 */
class CloudContentDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  protected $query_factory;

  /**
   * {@inheritdoc}
   */
  public function __construct(QueryFactory $query_factory, EntityManagerInterface $manager) {
    $this->query_factory = $query_factory;
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {

    $entity = $this->entity;

    return t('Are you sure you want to delete entity %name?', [
      '%name' => $entity->label(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {

    $entity = $this->entity;
    $url = $entity->urlInfo('collection');

    if (method_exists($entity, 'cloud_context')) {
      $url->setRouteParameter('cloud_context', $entity->cloud_context());
    }

    return $url;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {

    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $entity = $this->entity;
    $entity->delete();

    drupal_set_message(
      $this->t('content @type: deleted "@label".', [
        '@type'  => $entity->bundle(),
        '@label' => $entity->label(),
      ])
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
