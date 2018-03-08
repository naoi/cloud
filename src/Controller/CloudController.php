<?php

// Updated by yas 2016/06/23
// Updated by yas 2016/05/29.

namespace Drupal\cloud\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\cloud_server_template\Controller;

/**
 * Class CloudController.
 *
 * @package Drupal\cloud\Controller
 */
class CloudController {

  /**
   * Returns a form to get list of instances
   * under all the cloud together.
   *
   * @return return a dashboard form
   */
  function getInstanceList() {

    debug('Cloud::getInstanceList()');

    return [
      '#type' => 'markup',
      '#markup' => t('THIS IS A DEBUG MESSAGE - Cloud::getInstanceList()'),
    ];

/**
 * @TODO
  return \Drupal::formBuilder()->getForm('cloud_display_dashboard');
*/
  }

  /**
   * Returns a form to get list of server tempaltes
   * under all the server templates together.
   *
   * @return return a dashboard form
   */
  function getCloudServerTemplateList() {

    debug('Cloud::getCloudServerTemplateList()');

    // Call CloudServerTemplate::getCloudServerTemplateList

    return [
      '#type' => 'markup',
      '#markup' => t('THIS IS A DEBUG MESSAGE - Cloud::getCloudServerTemplateList()'),
    ];

/**
 * @TODO:
  return \Drupal::formBuilder()->getForm('cloud_display_dashboard');
*/

  }

  /**
   * To fetch the data of sub_clouds info.
   *
   * @return return a sub-cloud data and redirect to cloud dashboard page
   */
  function getData() {

    debug('Cloud::getData()');

    return [
      '#type' => 'markup',
      '#markup' => t('Hello Word - Cloud::getData()'),
    ];

    if (_cloud_is_update_allowed()) {
      cloud_update_all_cloud_data();
    }
    // Return to the Common Clouds Page.
    drupal_goto('clouds');
  }

  /**
   * AJAX callback for main cloud listing page.
   */
  function getInstanceListCallback() {

    debug('Cloud::getInstanceListCallback()');

    return [
      '#type' => 'markup',
      '#markup' => t('Hello Word - Cloud::getInstanceListCallback()'),
    ];

    // Retrieve the table from the cloud_display_dashboard form.
    $form = \Drupal::formBuilder()->getForm('cloud_display_dashboard');
    $output = \Drupal::service("renderer")->render($form);

    // Send only the body do not send the headers.
    $index_start = strrpos($output, '<tbody>');
    $index_end   = strrpos($output, '</tbody>');

    // No element present.
    if (isset($form['Nickname']) === FALSE) {

      $output = 'NULL';
    }
    else {

      $output = substr($output, $index_start, $index_end - $index_start);
      $output .= '</tbody>';
    }

    print Json::encode(['html' => $output]);

    exit();
  }

}
