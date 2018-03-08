<?php

/**
 * @file
 * Provides a set of trigger and action for cloud management
 * Works with Cloud module.
 *
 */

/**
 * Returns a form with listing of alerts
 * Build a form including table header and table body
 * @param $form_submit
 *        This is the form-submit submitted by list alerts form
 * @return return a form
 */
function cloud_alert_list($form, $form_submit) {

  if ( \Drupal::currentUser()->hasPermission('list alerts') === FALSE  ) {

    drupal_set_message(t('You are not authorized to access this page.'));
    return array();
  }

  $user = \Drupal::currentUser();

  // @FIXME
// The Assets API has totally changed. CSS, JavaScript, and libraries are now
// attached directly to render arrays using the #attached property.
// 
// 
// @see https://www.drupal.org/node/2169605
// @see https://www.drupal.org/node/2408597
// drupal_add_js(cloud_get_module_base() . 'js/cloud.js');


  $options = array(
    t('Nickname'  ),
    t('Metric'    ),
    t('Variable'  ),
  );

  $filter     = cloud_get_filter_value( $form_submit, 'filter'   );
  $filter     = trim($filter);

  $filter_col = cloud_get_filter_value( $form_submit, 'operation');
  $filter_col = isset($filter_col) && $filter_col ? $filter_col : 0; // Default: Alert Name

  if ( $filter_col == 0 ) {
    $column  = 'Alert Name';
    $sql_col = 'name'       ;
  }
  elseif ($filter_col == 1 ) {
    $column  = 'Metric'     ;
    $sql_col = 'metric'     ;
  }
  elseif ($filter_col == 2 ) {
    $column  = 'Variable'   ;
    $sql_col = 'variable'   ;
  }

  if ( isset($filter) ) {

    $query_args[] = $sql_col;
    $query_args[] = $filter  ;
  }
  else {
    $filter       = ' 1 ';
    $query_args[] = ' '   ;
  }

  //asort($options);

  $alerts_count = _cloud_alert_get_count_db();
  $filter_disabled = '';
  if ( $alerts_count < 2 ) {

    $filter_disabled = TRUE;
  }

  $form['options'] = array(
    '#prefix' => '<div class="container-inline">',
    '#type' => 'fieldset',
    '#suffix' => '</div>',
    // '#title' => t('Operations'), 
  );

  $form['options']['label'    ] = array(
    '#type' => 'item'     ,
    '#title' => t('Filter'),
  );
  $form['options']['operation'] = array(
    '#type' => 'select'   ,
    '#options' => $options,
    '#default_value' => $filter_col,
    '#disabled' => $filter_disabled,
  );
  $form['options']['filter'   ] = array(
    '#type' => 'textfield',
    '#size' => 40,
    '#default_value' => $filter,
    '#disabled' => $filter_disabled,
  );
  $form['options']['submit'   ] = array(
    '#type' => 'submit'   ,
    '#value' => t('Apply'),
    '#disabled' => $filter_disabled,
  );

  if (\Drupal::currentUser()->hasPermission('create alert') ) {

    $form['options']['alert'] = array(
      '#type' => 'submit',
      '#value' => t('Create'),
    );
  }

  $form['header'] = array(
    '#type' => 'value',
    '#value' => array(
      array(
        'data' => t('Nickname'),
        'field' => 'name',
        'sort' => 'asc',
        'class' => array('nickname-column'),
      ),
      array(
        'data' => t('Description'   ),
        'field' => 'description',
      ),
      array(
        'data' => t('Trigger on'    ),
        'field' => 'metric',
      ),
      array(
        'data' => t('Duration (min)'),
        'field' => 'duration_in_minutes',
      ),
      array(
        'data' => t('Created'       ),
        'field' => 'created',
      ),
      array(
        'data' => t('Updated'       ),
        'field' => 'updated',
      ),
      array(
        'data' => t('Action'        ),
        'class' => 'action-column',
      ),
    ),
  );

  $query = _cloud_alert_get();
  $query = $query
      ->extend('PagerDefault')
      ->extend('TableSort')
      ->orderByHeader($form['header']['#value']);

  $query
      ->condition($query_args[0], '%%' . $query_args[1] . '%%', 'like')
      ->fields('a')
      ->limit(CLOUD_ALERTS_PAGER_LIMIT);

  $result = $query->execute();


  $destination = drupal_get_destination();
  foreach ($result as $alert_obj) {

    // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['Name'   ][$alert_obj->alert_id] = array(
//       array('#markup' => l( $alert_obj->name, CLOUD_ALERTS_PATH . '/' . $alert_obj->alert_id . '/view', array('query' => array('id' => $alert_obj->alert_id)))),
//     );


    $form['Trigger'][$alert_obj->alert_id] = array(
      array('#markup' => t('@metric.@variable  @condition @threshold', array(
          '@metric' => $alert_obj->metric    ,
          '@variable' => $alert_obj->variable  ,
          '@condition' => $alert_obj->condition,
          '@threshold' => $alert_obj->threshold,
        ))),
    );

    $form['hdnName'            ][$alert_obj->alert_id] = array(
      '#type' => 'hidden',
      '#value' => addslashes($alert_obj->name),
    );
    $form['Description'        ][$alert_obj->alert_id] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $alert_obj->description));
    $form['Created'            ][$alert_obj->alert_id] = array('#markup' => format_date(strtotime($alert_obj->created), 'short'));
    $form['Updated'            ][$alert_obj->alert_id] = array('#markup' => format_date(strtotime($alert_obj->updated), 'short'));
    $form['duration_in_minutes'][$alert_obj->alert_id] = array('#markup' => t( $alert_obj->duration_in_minutes));
  }

  $form_state['#redirect']  = FALSE;

  return $form;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function theme_cloud_alert_list($variables) {
  $form = $variables['form'];

  if ( \Drupal::currentUser()->hasPermission('list alerts') === FALSE  ) {

    $output = \Drupal::service("renderer")->render($form);
    return $output;
  }

  $rows = array();
  if ( !empty($form['Name']) ) {
    foreach (\Drupal\Core\Render\Element::children($form['Name']) as $key) {

      $row = array();
      $row[] = array(
        'data' => \Drupal::service("renderer")->render($form['Name'][$key]),
        'class' => array('nickname-column'),
      );
      $row[] = \Drupal::service("renderer")->render($form['Description'        ][$key]);
      $row[] = \Drupal::service("renderer")->render($form['Trigger'            ][$key]);
      $row[] = \Drupal::service("renderer")->render($form['duration_in_minutes'][$key]);
      $row[] = \Drupal::service("renderer")->render($form['Created'            ][$key]);
      $row[] = \Drupal::service("renderer")->render($form['Updated'            ][$key]);

      $form['hdnName'][$key]['#value'] = !empty($form['hdnName'][$key]['#value']) ? $form['hdnName'][$key]['#value'] : '';
      $action_data = '';
      if (\Drupal::currentUser()->hasPermission('delete alert')) {
        $prop['onclick'] = cloud_get_messagebox('Are you sure you want to delete the alert "' . trim($form['hdnName'][$key]['#value']) . '" ?');

        $action_data .= cloud_display_action( 'images/icon_delete', t('Delete'), CLOUD_ALERTS_PATH . '/' . urlencode($key) . '/delete/'  , array('query' => array('id' => urlencode($key)), 'html' => TRUE), $prop['onclick']);
      }

      if (\Drupal::currentUser()->hasPermission('edit alert')) {

        $action_data .= cloud_display_action( 'images/icon_edit', t('Edit'), CLOUD_ALERTS_PATH . '/' . urlencode($key) . '/edit', array('query' => array('id' => urlencode($key)), 'html' => TRUE));
      }

      $row[] =  array(
        'data' => $action_data,
        'class' => 'action-column',
      );
      $rows[] = $row;
    }
  }

  while (0) {
    if ($form['pager']['#markup']) {

    }
    // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $reload_link = l(t('- Refresh Page -'),
//                    CLOUD_ALERTS_PATH . '/getdata',
//                    array('query' => array('src' => CLOUD_ALERTS_PATH . '/list'))
//                  );

    $ref_link = array(
      '#type' => 'item',
      '#prefix' => '<div id="link_reload" align="right">',
      '#suffix' => '</div>',
      '#markup' => $reload_link,
    );
  }

  $output  = \Drupal::service("renderer")->render($form['options']);
  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output .= theme('table', array('header' => $form['header']['#value'], 'rows' => $rows));

  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output .= theme('pager');

  $output .= drupal_render_children($form);

  return $output;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_list_submit($form_id, &$form_values) {

  $form_values = $form_values['values'];

  if ( $form_values['op'] == t('Create')) {

    return drupal_goto( CLOUD_ALERTS_PATH . '/create');

  }
  elseif ( $form_values['op'] == t('Apply') ) {

    $param_arr = array(
      'filter' => $form_values['filter'],
      'operation' => $form_values['operation'],
    );

    $destination = drupal_get_destination();
    $form_values['redirect']     = array($destination['destination'], array('query' => $param_arr));
  }

  return;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_delete($alert_id = '') {

  _cloud_alert_delete($alert_id);

  drupal_set_message(t('Alert been deleted successfully.'));
  drupal_goto(CLOUD_ALERTS_PATH . '/list');

  return;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_view($form, $form_submit, $alert_id = '') {

  if ( \Drupal::currentUser()->hasPermission('view alerts') === FALSE  ) {

    drupal_set_message(t('You are not authorized to access this page.'));
    drupal_goto(CLOUD_ALERTS_PATH . '/list');
    return;
  }

  $action = 'Add';
  $alerts_options = cloud_alert_get_options();

  $form['fieldset_alert_info'             ] = array(
    '#type' => 'fieldset',
    '#title' => t('Details'),
  );
  $form['name'       ]['name_label'       ] = array(
    '#type' => 'item',
    '#title' => t('Name'),
  );

  $form['description']['description_label'] = array(
    '#type' => 'item',
    '#title' => t('Description'),
  );
  $form['condition'  ]['condition_label'  ] = array(
    '#type' => 'item',
    '#title' => t('Condition'),
  );

  $condition_elem = array(
    '#type' => 'fieldset',
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
  );

  $condition_elem['metric_Info'     ] = array(
    '#value' => 'If '      ,
    '#size' => 20,
  );
  $condition_elem['metric_select'   ] = array(
    '#type' => 'select'   ,
    '#options' => $alerts_options['ALERTS_METRIC_OPTIONS'],
  );
  $condition_elem['variable_Info'   ] = array('#value' => ' . ');
  $condition_elem['variable_select'] = array(
    '#type' => 'select'   ,
    '#options' => $alerts_options['ALERTS_VARIABLE_OPTIONS'],
  );
  $condition_elem['condition_Info'  ] = array('#value' => ' is ');
  $condition_elem['condition_select'] = array(
    '#type' => 'select'   ,
    '#options' => $alerts_options['ALERTS_CONDITION_OPTIONS'],
  );
  $condition_elem['threshold_text'  ] = array(
    '#type' => 'textfield',
    '#size' => 10,
    '#maxlength' => 3,
    '#title' => t('Threshold'),
  );
  $condition_elem['threshold_unit'  ] = array('#value' => '');

  //duration in minutes
  $duration_options = range(0, 60);
  $form['duration_in_minutes']['duration_in_minutes_label'] = array(
    '#type' => 'item',
    '#title' => t('Duration'),
  );
  $duration_elem = array(
    '#type' => 'fieldset',
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
  );
  $duration_elem['duration_in_minutes_Info'  ] = array('#value' => 'For ');
  $duration_elem['duration_in_minutes_select'] = array(
    '#type' => 'select'  ,
    '#options' => $duration_options,
  );
  $duration_elem['duration_in_minutes_Info1'] = array('#value' => ' minutes , then escalate to ');
  $duration_elem['escalate_select'           ] = array(
    '#type' => 'select'  ,
    '#options' => $alerts_options['ALERTS_ESCALATIONS'],
  );

  if ($alert_id) {

    $query = _cloud_alert_get();
    $query
      ->condition('alert_id', $alert_id)
      ->fields('a');

    $result = $query->execute();

    $count  = 0;
    $alert_object = $result->fetchObject();

    $action = t('Edit');
    $form['alert_id'   ] = array(
      '#type' => 'hidden',
      '#value' => $alert_id,
    );
    $form['name'       ]['name_text'       ] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( isset_variable($alert_object->name))); //array('#type' => 'textfield', '#size' => 100    );
    $form['description']['description_text'] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( isset_variable($alert_object->description) ));
    $condition_elem['metric_select'   ] = array('#markup' => t( isset_variable($alert_object->metric   ) ));
    $condition_elem['variable_select'] = array('#markup' => t( isset_variable($alert_object->variable ) ));
    $condition_elem['condition_select'] = array('#markup' => t( isset_variable($alert_object->condition) ));
    $condition_elem['threshold_text'  ] = array('#markup' => t( isset_variable($alert_object->threshold) ));

    $duration_elem['escalate_select'  ] = array('#markup' => t( isset_variable(($alert_object->escalate )
                                                                  ?  $alert_object->escalate
                                                                  :  'critical') ));
    $duration_elem['duration_in_minutes_select'] = array('#markup' => t( isset_variable($alert_object->duration_in_minutes) ));
  }
  $form['condition'          ]['condition_content'] = $condition_elem;
  $form['duration_in_minutes']['duration_content'] = $duration_elem;


  $form['submit_buttons'] = array(
    '#type' => 'fieldset',
    '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
    '#suffix' => '</div></div>',
  );

  if (\Drupal::currentUser()->hasPermission('edit alert')) {

    // Edit Button
    // @FIXME
// url() expects a route name or an external URI.
// $location = check_url(url(CLOUD_ALERTS_PATH . '/' . $alert_id . '/edit')) . '&id=' . $alert_id;

    $action   = "document.forms[0].action='" . $location . "';";
    $onclick  = $action;
    $form['submit_buttons']['Edit'] = array(
      '#type' => 'submit',
      '#value' => t('Edit'),
      '#attributes' => array('onclick' => $onclick),
    );
  }

  if (\Drupal::currentUser()->hasPermission('delete alert')) {

    // Delete Button
    $confirm_msg = cloud_get_messagebox(t('Are you sure you want to delete the Alert "@alert_name" ?', array('@alert_name' => $alert_object->name)));
    // @FIXME
// url() expects a route name or an external URI.
// $location    = check_url(url(CLOUD_ALERTS_PATH . '/' . $alert_id . '/delete')) . '&id=' . $alert_id;

    $action      = "document.forms[0].action='" . $location . "';";
    $onclick     = $action . $confirm_msg;
    $form['submit_buttons']['Delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#attributes' => array('onclick' => $onclick),
    );
  }

  $form['submit_buttons']['Cancel'] = array(
    '#type' => 'submit',
    '#value' => t('List Alerts'),
  );

  return $form;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function theme_cloud_alert_view($variables) {
  $form = $variables['form'];

  if ( \Drupal::currentUser()->hasPermission('view alerts') === FALSE  ) {

    return;
  }

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['name']['name_label'] ),
      \Drupal::service("renderer")->render($form['name']['name_text'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['description']['description_label'] ),
      \Drupal::service("renderer")->render($form['description']['description_text'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['condition']['condition_label'  ] ),
      \Drupal::service("renderer")->render($form['condition']['condition_content'] ),
    ),
    //duration_in_minutes_Info
    array(
      \Drupal::service("renderer")->render($form['duration_in_minutes']['duration_in_minutes_label'] ),
      \Drupal::service("renderer")->render($form['duration_in_minutes']['duration_content'         ] ),
    ),
  );

  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $table = theme('table', array('header' => NULL, 'rows' => $rows));

  $form['fieldset_alert_info']['#children'] = $table;

  //cf. Waning by Coder module: Potential problem: when FAPI element '#type' is set to 'markup' (default), '#value' only accepts filtered text, be sure to use check_plain(), filter_xss() or similar to ensure your $variable is fully sanitized.
  //$form['fieldset_alert_info']['list'] = array('#type' => 'markup', '#value' => $table);

  $output  = \Drupal::service("renderer")->render($form['submit_buttons']);
  $output .= \Drupal::service("renderer")->render($form['fieldset_alert_info']);
  $output .= drupal_render_children($form);

  return $output;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_create($form, $form_submit = '', $alert_id = '') {

  $action = t('Add');
  $alerts_options = cloud_alert_get_options();

  $form['fieldset_alert_info'] = array('#type' => 'fieldset' /* '#title' => t('Alert Info') */);
  $form['name']['name_label'] = array(
    '#type' => 'item'     ,
    '#title' => t('Name'),
    '#required' => TRUE,
  );
  $form['name']['name_text'  ] = array(
    '#type' => 'textfield',
    '#size' => 100,
  );

  $form['description']['description_label'] = array(
    '#type' => 'item',
    '#title' => t('Description'),
  );
  $form['description']['description_text'] = array('#type' => 'textarea');

  $form['condition']['condition_label']     = array(
    '#type' => 'item',
    '#title' => t('Condition'),
    '#required' => TRUE,
  );

  $condition_elem = array(
    '#type' => 'fieldset',
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
  );

  $condition_elem['metric_Info'     ] = array(
    '#value' => 'If '      ,
    '#size' => 20,
  );
  $condition_elem['metric_select'   ] = array(
    '#type' => 'select'   ,
    '#options' => $alerts_options['ALERTS_METRIC_OPTIONS'],
  );
  //$form['metric' ]['metric_IPs'     ] = array( '#type'  => 'textfield', '#size' => '14'  , '#title' => t('IPs') , '#default_value' => '0.0.0.0/32' );
  $condition_elem['variable_Info'   ] = array('#value' => ' . ');
  $condition_elem['variable_select'] = array(
    '#type' => 'select'   ,
    '#options' => $alerts_options['ALERTS_VARIABLE_OPTIONS'],
  );
  $condition_elem['condition_Info'  ] = array('#value' => ' is ');
  $condition_elem['condition_select'] = array(
    '#type' => 'select'   ,
    '#options' => $alerts_options['ALERTS_CONDITION_OPTIONS'],
  );
  $condition_elem['threshold_text'  ] = array(
    '#type' => 'textfield',
    '#size' => 10,
    '#maxlength' => 10,
    '#title' => t('Threshold'),
  );
  $condition_elem['threshold_unit'  ] = array('#value' => '');

  //duration in minutes
  $duration_options = range(0, 60);
  unset($duration_options[0]);
  $form['duration_in_minutes']['duration_in_minutes_label'] = array(
    '#type' => 'item',
    '#title' => t('Duration'),
  );
  $duration_elem = array(
    '#type' => 'fieldset',
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>',
  );
  $duration_elem['duration_in_minutes_Info'  ] = array('#value' => 'For ');
  $duration_elem['duration_in_minutes_select'] = array(
    '#type' => 'select'  ,
    '#options' => $duration_options,
  );
  $duration_elem['duration_in_minutes_Info1'] = array('#value' => ' minutes , then escalate to ');
  $duration_elem['escalate_select'           ] = array(
    '#type' => 'select'  ,
    '#options' => $alerts_options['ALERTS_ESCALATIONS'],
  );

  if ($alert_id) {

    $query      = _cloud_alert_get();
    $query
      ->condition('alert_id', $alert_id)
      ->fields('a');

    $result = $query->execute();

    $count  = 0;

    $alert_object = $result->fetchObject();


    $action = t('Edit');
    $form['alert_id'] = array(
      '#type' => 'hidden',
      '#value' => $alert_id,
    );
    $form['name']['name_text']['#default_value'] = isset_variable($alert_object->name);
    $form['description']['description_text']['#default_value'] = isset_variable($alert_object->description );

    $condition_elem['metric_select'   ]['#default_value'] = isset_variable($alert_object->metric    );
    $condition_elem['variable_select']['#default_value'] = isset_variable($alert_object->variable  );
    $condition_elem['condition_select']['#default_value'] = isset_variable($alert_object->condition );
    $condition_elem['threshold_text'  ]['#default_value'] = isset_variable($alert_object->threshold );

    $duration_elem['escalate_select']['#default_value'] = isset_variable($alert_object->escalate  );
    $duration_elem['duration_in_minutes_select']['#default_value'] = isset_variable($alert_object->duration_in_minutes );
  }

  $form['condition'          ]['condition_content'] = $condition_elem;
  $form['duration_in_minutes']['duration_content'] = $duration_elem;


  $form['submit_buttons'] = array(
    '#type' => 'fieldset',
    '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
    '#suffix' => '</div></div>',
  );

  $form['submit_buttons'][$action]  = array(
    '#type' => 'submit',
    '#value' => t($action),
  );
  $form['submit_buttons']['Cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function theme_cloud_alert_create($variables) {
  $form = $variables['form'];


  $output = '';
  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['name']['name_label'] ),
      \Drupal::service("renderer")->render($form['name']['name_text'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['description']['description_label'] ),
      \Drupal::service("renderer")->render($form['description']['description_text'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['condition']['condition_label'  ] ),
      \Drupal::service("renderer")->render($form['condition']['condition_content'] ),
    ),
    //duration_in_minutes_Info
    array(
      \Drupal::service("renderer")->render($form['duration_in_minutes']['duration_in_minutes_label'] ),
      \Drupal::service("renderer")->render($form['duration_in_minutes']['duration_content'         ] ),
    ),
  );


  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $table = theme('table', array('header' => NULL, 'rows' => $rows));

  $form['fieldset_alert_info']['#children'] = $table;

  //cf. Waning by Coder module: Potential problem: when FAPI element '#type' is set to 'markup' (default), '#value' only accepts filtered text, be sure to use check_plain(), filter_xss() or similar to ensure your $variable is fully sanitized.
  //$form['fieldset_alert_info']['list'] = array('#type' => 'markup', '#value' => $table);

  $output .= \Drupal::service("renderer")->render($form['fieldset_alert_info']);
  $output .= \Drupal::service("renderer")->render($form['submit_buttons']);
  $output .= drupal_render_children($form);

  return $output;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_create_validate($form_id, $form_values) {

  $form_values = $form_values['values'];

  if ( $form_values['op'] == t('Add')
    ||     $form_values['op'] == t('Edit') ) {

    //print_r($form_values['name_text']); die;
    if ( empty($form_values['name_text']) ) {

      form_set_error('', t('You must enter valid Alert Name') );
    }
    else {


      $query        = _cloud_alert_get();
      $query
      ->condition('name', $form_values['name_text'], 'like')
      ->fields('a');

      $result = $query->execute()->fetchObject();

      if ($result) {

        $alert_id = $result->alert_id;
        if ($alert_id != $form_values['alert_id']) {

          form_set_error('', t('Name is already used by a Alert.'));
        }
      }
    }

    if ( !preg_match(CLOUD_ALERTS_VALID_NUMBER, $form_values['threshold_text']) ) {

      form_set_error('', t('You must enter valid threshold value'));
    }
    elseif ( empty($form_values['duration_in_minutes_select']) ) {

      form_set_error('', t('You must enter valid duration time in minutes'));
    }
  }

  return;
}


/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_create_submit($form_id, $form_values) {

  $form_values = $form_values['values'];
  if ( $form_values['op'] == t('Cancel')) {

    drupal_goto( CLOUD_ALERTS_PATH . '/list');

    return;

  }
  elseif ($form_values['op'] == t('Add')) {

    _cloud_alert_insert($form_values);

    drupal_set_message(t('Alert has been saved.'));
    drupal_goto(CLOUD_ALERTS_PATH . '/list');

  }
  elseif ($form_values['op'] == t('Edit')) {

    $alert_id = $form_values['alert_id'];
    _cloud_alert_update($form_values, $alert_id );
    drupal_set_message(t('Alert has been saved.'));
    drupal_goto(CLOUD_ALERTS_PATH . '/list');
  }

  return;
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function cloud_alert_view_submit($form_id, $form_values) {

  $form_values = $form_values['values'];

  if ( $form_values['op'] == t('List Alerts')) {
    drupal_goto( CLOUD_ALERTS_PATH . '/list');

    return;
  }
}


function _cloud_alert_getdata($src = '') {

  if ( $src == NULL
    ||     empty($src) ) {
    drupal_goto( CLOUD_ALERTS_PATH . '/list' );
  }
  else {

    drupal_goto( $src);
  }
}
