<?php

// Updated by yas 2015/06/08
// updated by yas 2015/06/05
// created by yas 2015/06/03.
namespace Drupal\cloud\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CloudConfigForm.
 *
 * @package Drupal\cloud\Form
 */
class CloudConfigForm extends EntityForm {

  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   *   Necessary for Dependency Injection
   */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   * Necessary for Dependency Injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   * For abstract method.
   */
  public function getFormId() {
    // @FIXME

    return $this->entity->getEntityType()->id() . ".admin";
  }

  /**
   * {@inheritdoc}
   * For abstract method.
   */
  public function getEditableConfigNames() {
    // @FIXME

    return ['cloud.' . $this->entity->getEntityType()->id()];
  }

  /**
   * Override actions()
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param $cloud_context
   *   A unique machine name for the cloud provider
   *
   * @return array
   *   Form definition array.
   */
  public function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $entity = $this->entity;
    foreach ($actions as $key => $action) {
      if (isset($actions[$key]['#url'])
      && method_exists($this->entity, 'cloud_context')) {
        $actions[$key]['#url']->setRouteParameter('cloud_context', $entity->cloud_context());
      }
    }
    return $actions;
  }

  /**
   *
   */
  public function exist($id) {
    /*
    $entity = \Drupal::entityQuery($this->entity->getEntityType()->id())
    ->condition('id', $id)
    ->execute();
     */
    // Dependency Injection Version.
    $result = $this->entityQuery
                   ->get($this->entity->getEntityType()->id())
                   ->condition('id', $id)
                   ->execute();

    return (bool) $result;
  }

}
