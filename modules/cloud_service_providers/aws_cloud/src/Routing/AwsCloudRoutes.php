<?php
// Created by yas 2016/06/21.

/**
 * @file
 * Contains \Drupal\aws_cloud\Routing\AwsCloudRoutes.
 */

namespace Drupal\aws_cloud\Routing;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Controller\ControllerBase;

use Drupal\aws_cloud\Aws\Config\ConfigInterface;
use Drupal\aws_cloud\Entity\Config\Config;

use Symfony\Component\Routing\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines dynamic routes.
 */
class AwsCloudRoutes {

//  /**
//   * This function and the next are part of the dependency injection pattern.
//   */
//  public function __construct(QueryFactory $entity_query) {
//    $this->entity_query = $entity_query;
//    $this->now = (new \DateTime())->getTimestamp();
//  }

//  /**
//   * Dependency Injection.
//   */
//  public static function create(ContainerInterface $container) {
//    return new static(
//      // User the $container to get a query factory object.
//      // This object let us create query objects.
//      $container->get('entity.query')
//    );
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public function routes() extends ControllerBase {
//
//    $cloud_contexts = $this->entity_query->get('cloud_context')
//                        ->execute();
//var_dump($cloud_contexts);
//exit;
//    $routes = array();
//    // Declares a single route under the name 'example.content'.
//    // Returns an array of Route objects.
//    $routes['entity.aws_cloud_instance.collection' . '.aaaa'] = new Route(
//      // Path to attach this route to:
//      '/clouds/aws_cloud/{cloud_context}/instance',
//      // Route defaults:
//      array(
//        '_entity_list' => 'aws_cloud_instance'',
//        '_title' => 'AWS Cloud Instance'
//      ),
//      // Route requirements:
//      array(
//        '_permission'  => 'list aws cloud instance',
//      )
//    );
//    return $routes;
//  }

/**
 * {@inheritdoc}
 */
  public function routes() {
    $routes = array();

    // testing
    $routes['entity.aws_cloud_instance.canonical'] = new Route(
      '/clouds/aws_cloud/aws_us_west_1/instance/{aws_cloud_instance}',
      array(
        '_entity_view' => 'aws_cloud_instance',
        '_title' => 'AWS Cloud Instance',
      ),
      array(
        '_permission'  => 'view aws cloud instance',
      )
    );

    return $routes;
  }

}