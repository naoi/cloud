<?php

// Updated by yas 2015/06/23
// Created by yas 2015/05/30.

namespace Drupal\cloud_server_template\Controller;

/**
 * Class CloudServerTemplateController.
 *
 * @package Drupal\cloud_server_template\Controller
 */
use Drupal\Core\Controller\ControllerBase;

\Drupal::moduleHandler()->loadInclude('cloud', 'inc', 'cloud.constants');
/**
 *
 */
class CloudServerTemplateController extends ControllerBase {

  /**
   * Returns a form to get list of server tempaltes
   * under all the server templates together.
   *
   * @return return a dashboard form
   */
  function getCloudServerTemplateList() {

    debug('CloudServerTemplateController::getCloudServerTemplateList()');

    return [
      '#type' => 'markup',
      '#markup' => t(''),
    ];

/**
 * @TODO:
  return \Drupal::formBuilder()->getForm('cloud_display_dashboard');
*/

  }
}
