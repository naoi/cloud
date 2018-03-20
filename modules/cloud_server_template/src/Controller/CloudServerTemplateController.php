<?php

namespace Drupal\cloud_server_template\Controller;

use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;
use Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CloudServerTemplateController.
 *
 *  Returns responses for Cloud Server Template routes.
 */
class CloudServerTemplateController extends ControllerBase implements ContainerInjectionInterface, CloudServerTemplateControllerInterface {

  /**
   * The ServerTemplatePluginManager
   * @var \Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginManager
   */
  protected $serverTemplatePluginManager;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs an OperationsController object.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match
   * @param \Drupal\cloud_server_template\Controller\CloudServerTemplatePluginManagerInterface $server_template_plugin_manager
   *   The server template plugin manager
   */
  public function __construct(RouteMatchInterface $route_match, CloudServerTemplatePluginManagerInterface $server_template_plugin_manager) {
    $this->routeMatch = $route_match;
    $this->serverTemplatePluginManager = $server_template_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('plugin.manager.cloud_server_template_plugin')
    );
  }

  /**
   * Displays a Cloud Server Template  revision.
   *
   * @param int $cloud_server_template_revision
   *   The Cloud Server Template  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($cloud_server_template_revision) {
    $cloud_server_template = $this->entityManager()->getStorage('cloud_server_template')->loadRevision($cloud_server_template_revision);
    $view_builder = $this->entityManager()->getViewBuilder('cloud_server_template');

    return $view_builder->view($cloud_server_template);
  }

  /**
   * Page title callback for a Cloud Server Template  revision.
   *
   * @param int $cloud_server_template_revision
   *   The Cloud Server Template  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($cloud_server_template_revision) {
    $cloud_server_template = $this->entityManager()->getStorage('cloud_server_template')->loadRevision($cloud_server_template_revision);
    return $this->t('Revision of %title from %date', ['%title' => $cloud_server_template->label(), '%date' => format_date($cloud_server_template->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Cloud Server Template .
   *
   * @param \Drupal\cloud_server_template\Entity\CloudServerTemplateInterface $cloud_server_template
   *   A Cloud Server Template  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CloudServerTemplateInterface $cloud_server_template) {
    $account = $this->currentUser();
    $langcode = $cloud_server_template->language()->getId();
    $langname = $cloud_server_template->language()->getName();
    $languages = $cloud_server_template->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $cloud_server_template_storage = $this->entityManager()->getStorage('cloud_server_template');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $cloud_server_template->label()]) : $this->t('Revisions for %title', ['%title' => $cloud_server_template->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all cloud server template revisions") || $account->hasPermission('administer cloud server template entities')));
    $delete_permission = (($account->hasPermission("delete all cloud server template revisions") || $account->hasPermission('administer cloud server template entities')));

    $rows = [];

    $vids = $cloud_server_template_storage->revisionIds($cloud_server_template);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\cloud_server_template\CloudServerTemplateInterface $revision */
      $revision = $cloud_server_template_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $cloud_server_template->getRevisionId()) {
          $link = $this->l($date, new Url('entity.cloud_server_template.revision', ['cloud_server_template' => $cloud_server_template->id(), 'cloud_server_template_revision' => $vid]));
        }
        else {
          $link = $cloud_server_template->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.cloud_server_template.translation_revert', ['cloud_server_template' => $cloud_server_template->id(), 'cloud_server_template_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.cloud_server_template.revision_revert', ['cloud_server_template' => $cloud_server_template->id(), 'cloud_server_template_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.cloud_server_template.revision_delete', ['cloud_server_template' => $cloud_server_template->id(), 'cloud_server_template_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['cloud_server_template_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function launch(CloudServerTemplateInterface $cloud_server_template) {
    /* @var \Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginInterface $plugin */
    $plugin = $this->serverTemplatePluginManager->loadPluginVariant($cloud_server_template->cloud_context());
    $plugin->launch($cloud_server_template);
    $build = [
      '#markup' => t('Hi from plugin ') . $plugin->getEntityBundleName(),
    ];
    return $build;
  }
}
