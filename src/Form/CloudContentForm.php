<?php


// Updated by yas 2016/05/31
// Updated by yas 2016/05/30
// Updated by yas 2015/06/08
// updated by yas 2015/06/05
// created by yas 2015/06/03.
namespace Drupal\cloud\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the Cloud entity edit forms.
 *
 * @ingroup cloud
 */
class CloudContentForm extends ContentEntityForm {

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
   * Override actions()
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param $cloud_context
   *   Unique machine name for cloud
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
   * Overrides \Drupal\Core\Entity\EntityFormController::submit().
   */
  public function submit(array $form, FormStateInterface $form_state) {
    // Build the entity object from the submitted values.
    $entity = parent::submit($form, $form_state);

    return $entity;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $status  = 'error';
    $message = $this->t('The @label "%label" was not saved.', [
      '@label' => $entity->getEntityType()->getLabel(),
      '%label' => $entity->label(),
    ]);
    if ($entity->save()) {

      $status  = 'status';
      $message = $this->t('The @label "%label" has been saved.', [
        '@label' => $entity->getEntityType()->getLabel(),
        '%label' => $entity->label(),
      ]);
    }

    drupal_set_message($message, $status);

    if (method_exists($entity, 'cloud_context')) {
      $form_state->setRedirectUrl($entity
                 ->urlInfo('collection')
                 ->setRouteParameter('cloud_context', $entity->cloud_context()));
    }
    else {
      $form_state->setRedirectUrl($entity
                 ->urlInfo('collection'));
    }
  }

}
