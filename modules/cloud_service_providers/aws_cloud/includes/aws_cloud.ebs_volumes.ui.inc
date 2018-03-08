<?php

/**
 * @file
 * aws_lib UI for EBS Volumes
 *
 */

/**
 *  Display Volumes list
 *
 * @param  $form_submit
 *              Form
 * @param  $cloud_context
 *          Instance belongs to this sub-cloud
 * @return
 */
function aws_cloud_display_ebs_volumes_list($form_submit = '', $form_state, $cloud_context) {

  // @FIXME
// The Assets API has totally changed. CSS, JavaScript, and libraries are now
// attached directly to render arrays using the #attached property.
// 
// 
// @see https://www.drupal.org/node/2169605
// @see https://www.drupal.org/node/2408597
// drupal_add_js(cloud_get_module_base() . 'js/cloud.js');

  // @FIXME
// The Assets API has totally changed. CSS, JavaScript, and libraries are now
// attached directly to render arrays using the #attached property.
// 
// 
// @see https://www.drupal.org/node/2169605
// @see https://www.drupal.org/node/2408597
// drupal_add_js( drupal_get_path('module', 'aws_cloud')
//     . CLOUD_PATH_SEPARATOR
//     . 'js/aws_cloud.auto_refresh_list_volumes.js'
//     );


  $column = 'volume_id';

  $options = array(
    t('Nickname'),
    t('ID'),
    t('Zone'),
  );

  $sql_col = '';

  $filter     = isset($_REQUEST['filter'   ]) ? $_REQUEST['filter'   ] : '';
  $filter_col = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : 0  ; // default: Nickname
  $filter     = trim($filter);

  if ( $filter_col == 0 ) {
    $column  = 'Nickname'    ;
    $sql_col = 'nickname'    ;
  }
  elseif ($filter_col == 1 ) {
    $column  = 'Volume ID'   ;
    $sql_col = 'volume_id'   ;
  }
  elseif ($filter_col == 2 ) {
    $column  = 'Zone'        ;
    $sql_col = 'vol_zone'    ;
  }
  elseif ($filter_col == 3 ) {
    $column  = 'Instance'    ;
    $sql_col = 'instance_id';
  }
  elseif ($filter_col == 4 ) {
    $column  = 'Status'      ;
    $sql_col = 'status'      ;
  }

  $query_args = array();

  if ( isset($filter) ) {
    $query_args[] = $sql_col;
    $query_args[] = $filter;
    $query_args[] = $cloud_context;
  }
  else {
    $filter = ' 1 ';
    $query_args[] = ' ';
    $query_args[] = $cloud_context;
  }
  
  $form['filter_hdn'] = array(
      '#type' => 'hidden',
      '#value' => $filter,
  );
  
  $form['operation_hdn'] = array(
      '#type' => 'hidden',
      '#value' => $filter_col,
  );
  $form['options'] = array(
    '#prefix' => '<div class="container-inline">',
    '#type' => 'fieldset',
    '#suffix' => '</div>',
  );

  $volume_count = _aws_cloud_get_volumes_list_count($cloud_context);
  $filter_disabled  = '';
  if ( $volume_count < 2 ) {
    $filter_disabled = TRUE;
  }

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
    '#size' => 40      ,
    '#default_value' => $filter    ,
    '#disabled' => $filter_disabled,
  );
  $form['options']['submit'   ] = array(
    '#type' => 'submit'   ,
    '#value' => t('Apply')                               ,
    '#disabled' => $filter_disabled,
  );
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' create volume') ) {
    $form['options']['new_key'] = array(
      '#type' => 'submit',
      '#value' => t('Create'),
    );
  }

  $form['header'] = array(
    '#type' => 'value',
    '#value' => array(
      array(
        'data' => t('Nickname'),
        'field' => 'nickname'           ,
        'class' => array('nickname-column')    ,
        'sort' => 'asc',
      ),
      array(
        'data' => t('ID'      ),
        'field' => 'volume_id',
      ),
      array(
        'data' => t('Status'  ),
        'field' => 'status',
      ),
      array(
        'data' => t('Zone'    ),
        'field' => 'vol_zone',
      ),
      array(
        'data' => t('Size'    ),
        'field' => 'vol_size',
      ),
      array(
        'data' => t('Instance'),
        'field' => 'instance_nickname',
      ),
      array(
        'data' => t('Device'  ),
        'field' => 'device',
      ),
      array(
        'data' => t('Created' ),
        'field' => 'created',
      ),
      array(
        'data' => t('Actions' ),
        'class' => 'action-column',
      ),
    ),
  );

  $query  = _aws_cloud_get_describe_volumes_list_query($cloud_context);

  $query = $query
      ->condition('c.cloud_type', $cloud_context, '=')
      ->condition($sql_col, '%' . $filter . '%', 'like')
      ->extend('TableSort')
      ->extend('PagerDefault')
      ->orderByHeader($form['header']['#value'])
      ->limit(AWS_CLOUD_PAGER_LIMIT);

  $result = $query->execute();
  $cloud_menu_path = cloud_get_menu_path($cloud_context);
  
  if (isset($result)) {
    foreach ($result as $vol) {
      // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['Nickname'][$vol->volume_id] = array('#markup' => l($vol->nickname, $cloud_menu_path . '/ebs_volumes/describe', array('query' => array('vol_id' => urlencode($vol->volume_id)))));

      $form['ID'][$vol->volume_id] = array('#markup' => t($vol->volume_id));
      $form['Status'][$vol->volume_id] = array('#markup' => t($vol->status));
      $form['Zone'][$vol->volume_id] = array('#markup' => t($vol->vol_zone));
      $form['Size'][$vol->volume_id] = array('#markup' => t($vol->vol_size));
      // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['Instance'][$vol->volume_id] = array('#markup' => l($vol->instance_nickname, $cloud_menu_path . '/instances/describe/' . $vol->instance_id . '/info', array('query' => array('instance_id' => urlencode($vol->instance_id)))));

      $form['Device'][$vol->volume_id] = array('#markup' => t($vol->device));
      $form['VolName'][$vol->volume_id] = array('#type' => 'hidden', '#value' => $vol->nickname);
      
      $created = '' ;
      if ($vol->created) {
        $created = format_date(strtotime($vol->created), 'short');
      }
      $lock_status = 'Unlocked';
      $form['Created'][$vol->volume_id] = array('#markup' => $created);
      if (empty($vol->instance_id) === FALSE ) {
        $lock_status = _aws_cloud_get_lock_status_by_id_query($cloud_context, $vol->instance_id);
        $form['locked'  ][$vol->volume_id] = array(
          '#type' => 'hidden',
          '#value' => $lock_status,
        );
        if ($lock_status === 'Lock' ) {
          $form['Nickname'][$vol->volume_id]['#markup'] = $form['Nickname'][$vol->volume_id]['#markup'] . '<a class=lockIcon title=Lock>&nbsp;&nbsp;&nbsp;&nbsp;</a>';
        }
      }
      else {
        $form['locked'][$vol->volume_id] = array(
          '#type' => 'hidden',
          '#value' => 'Available',
        );
      }
      $form['Actions'][$vol->volume_id] = array(
        '#markup' => aws_cloud_volumes_action($cloud_context, array('volume_id' => $vol->volume_id, 'volume_name' => $vol->nickname, 'volume_status' => $vol->status, 'locked' => $lock_status)),
      );
    }
  }
  // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['refresh_page'] = array(
//     '#type' => 'item',
//     '#prefix' => '<div id="link_reload" align="right">',
//     '#suffix' => '</div>',
//     '#markup' => l(t('- Refresh Page -'), $cloud_menu_path . '/get_volumes_data', array()),
//   );


  $form['cloud_context'] = array(
    '#type' => 'hidden',
    '#value' => $cloud_context,
  );
  
  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $form['pager'] = array('#value' => theme('pager', array('tags' => NULL, 'element' => 0)));

  $form['#redirect']  = FALSE;

  return $form;
}


/**
 * Theme function for volumes listing page
 * @param  $form
 * @return
 */
function theme_aws_cloud_display_ebs_volumes_list($form) {
  $form = $form['form'];
  $cloud_context = $form['cloud_context']['#value'];
  $cloud_menu_path = cloud_get_menu_path($cloud_context);
  
  $output = '';
  
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' create volume') ) {
    $output .= \Drupal::service("renderer")->render($form['new_key']);
  }
  $output .= \Drupal::service("renderer")->render($form['options']);
  
  $rows = array();
  if (isset($form['Nickname'])) {
    foreach (\Drupal\Core\Render\Element::children($form['Nickname']) as $key) {
      $rows[] = array(
        array(
          'data' => \Drupal::service("renderer")->render($form['Nickname'][$key]),
          'class' => array('nickname-column'),
        ),
        \Drupal::service("renderer")->render($form['ID'][$key]),
        \Drupal::service("renderer")->render($form['Status'][$key]),
        \Drupal::service("renderer")->render($form['Zone'][$key]),
        \Drupal::service("renderer")->render($form['Size'][$key]),
        \Drupal::service("renderer")->render($form['Instance'][$key]),
        \Drupal::service("renderer")->render($form['Device'][$key]),
        \Drupal::service("renderer")->render($form['Created'][$key]),
        array('data' => \Drupal::service("renderer")->render($form['Actions'][$key]), 'class' => array('action-column')),
      );
    }
  }

  $table_attr = array();
  $table_attr['id'] = 'volumes_list_table';
  // @FIXME
// url() expects a route name or an external URI.
// $table_attr['autoupdate_url'] = url( $cloud_menu_path . '/callback_get_volumes_list' );

  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output .= theme('table', array('header' => $form['header']['#value'], 'rows' => $rows, 'attributes' => $table_attr));


  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output .= theme('pager', array('tags' => array()));

  $output .= \Drupal::service("renderer")->render($form['refresh_page']);
  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Function to build action links
 * volume_name, volume_id, volume_status, locked
 */
function aws_cloud_volumes_action($cloud_context, $volume_info) {
  $action_data  = array();
  $cloud_menu_path = cloud_get_menu_path($cloud_context);
  
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' create snapshot')) {
    $action_data[] = cloud_display_action('images/icon_play.png', 'create snapshot', $cloud_menu_path . '/ebs_volumes/create_snapshot', array('query' => array('vol_id' => urlencode($volume_info['volume_id']), 'volume_name' => urlencode($volume_info['volume_name']), 'html' => TRUE))); 
  }
  
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' delete volume')) {
    if ($volume_info['volume_status'] == 'available') {
      $prop_delete = cloud_get_messagebox('Are you sure you want to delete the EBS-Volume "' . $volume_info['volume_name'] . '" ?');
      $action_data[] = cloud_display_action('images/icon_delete.png', t('Delete'), $cloud_menu_path . '/ebs_volumes/delete', array('query' => array('vol_id' => urlencode($volume_info['volume_id']))  , 'html' => TRUE), $prop_delete);
    }
  }
  
  if ($volume_info['volume_status'] !== 'available' && $volume_info['locked'] !== 'Lock' && \Drupal::currentUser()->hasPermission($cloud_context . ' detach volume') ) {
    $prop_detach = cloud_get_messagebox('Are you sure you want to detach the EBS-Volume "' . $volume_info['volume_id'] . '" ?');
    $action_data[] = cloud_display_action('images/icon_clear.png', t('Detach'), $cloud_menu_path . '/ebs_volumes/detach', array('query' => array('destination' => \Drupal\Core\Url::fromRoute("<current>")->toString(), 'vol_id' => urlencode($volume_info['volume_id'])), 'html' => TRUE), $prop_detach);
  }
  
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' delete volume')) {
    $action_data[] = cloud_display_action('images/icon_edit.png', t('Edit'), $cloud_menu_path . '/ebs_volumes/describe', array('query' => array('vol_id' => urlencode($volume_info['volume_id'])), 'html' => TRUE));
  }
  
  $user_actions = \Drupal::moduleHandler()->invokeAll('aws_cloud_volume_action_data', [$volume_info]);
  $action_data = array_merge($action_data, $user_actions);
  return implode($action_data);
}

/**
 * AJAX Function for Volume Listing Page
 * @todo: Find a better way of rendering the form body
 */
function _aws_cloud_callback_get_volumes_list($cloud_context) {
  $form = \Drupal::formBuilder()->getForm('aws_cloud_display_ebs_volumes_list', $cloud_context);
  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output = theme('aws_cloud_display_ebs_volumes_list', $form);

  $index_start = strrpos( $output, '<tbody>');
  $index_end   = strrpos( $output, '</tbody>');
  if (isset($form['Nickname']) === FALSE || sizeof($form['Nickname']) == 0) { // No element present
    $output = 'NULL';
  }
  else {
    $output = substr( $output, $index_start, $index_end - $index_start );
    $output .= '</tbody>';
  }
  
  print \Drupal\Component\Serialization\Json::encode(array('html' => $output));
  exit();
}

/**
 * Function to submit the volume
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_display_ebs_volumes_list_submit($form_id, &$form_state) {

  $form_values =& $form_state['values'];
  $cloud_menu_path = cloud_get_menu_path($form_values['cloud_context']);
  
  if ($form_values['op'] == t('Create')) {
    $form_state['redirect'] = $cloud_menu_path . '/ebs_volumes/create';
  }
  elseif ($form_values['op'] == t('List Volumes')) {
    $form_state['redirect'] = $cloud_menu_path . '/ebs_volumes';
  }
  elseif ($form_values['op'] == t('Apply')) {
    $param_arr = array(
      'filter' => $form_values['filter'],
      'operation' => $form_values['operation'],
    );
    $form_state['redirect'] = array(\Drupal\Core\Url::fromRoute("<current>")->toString(), array('query' => $param_arr));
  }
}


/**
 * Create Volume
 *
 * @param  $form_submit
 *              Form to be object
 * @param  $cloud_context
 *                 sub-cloud
 * @return
 */
function aws_cloud_create_ebs_volume($form, $form_submit = '', $cloud_context) {

  $form['fieldset_volume_info'             ] = array('#type' => 'fieldset' /* '#title' => t('Volume Info') */);

  $ss_id = isset( $_REQUEST['ss_id'] ) ? $_REQUEST['ss_id'] : '';
  $form['Volume_details']['Name_label'     ] = array(
    '#type' => 'item',
    '#title' => t('Nickname'),
    '#required' => TRUE,
  );
  $form['Volume_details']['Name_text'      ] = array('#type' => 'textfield');

  $snapshot_options = _aws_cloud_get_snapshot_options($cloud_context);
  $form['Volume_details']['Snapshot_label'] = array(
    '#type' => 'item',
    '#title' => t('Snapshot'),
  );
  $form['Volume_details']['Snapshot_select'] = array(
    '#type' => 'select',
    '#options' => $snapshot_options,
  );

  $zone_options = aws_cloud_get_zones($cloud_context);
  unset($zone_options['any']);

  $form['Volume_details']['zone_label'     ] = array(
    '#type' => 'item',
    '#title' => t('Availability Zone'),
  );
  $form['Volume_details']['zone_select'    ] = array(
    '#type' => 'select',
    '#options' => $zone_options,
  );

  $form['Volume_details']['Size_label'     ] = array(
    '#type' => 'item',
    '#title' => t('Size in GB'),
    '#required' => TRUE,
  );
  $form['Volume_details']['Size_text'      ] = array('#type' => 'textfield');

  if ( empty($ss_id) === FALSE ) { // If Snapshot id is present set the default values
    $form['Volume_details']['Snapshot_select']['#default_value'] =  $ss_id;
    $snapshot_data = _aws_cloud_get_snapshots_by_id_db($cloud_context, $ss_id );
    $size          = $snapshot_data['vol_size'];
    $size_arr      = preg_split( '[ ]', $size );
    if ( isset($size_arr[0]) ) {

      $size = $size_arr[0];
      $form['Volume_details']['Size_text']['#default_value'] =  $size  ;
    }

    $form['Volume_details']['zone_select']['#default_value'] =  $snapshot_data['vol_zone']  ;
  }

  $form['submit_buttons'] = array(
    '#type' => 'fieldset',
    '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
    '#suffix' => '</div></div>',
  );

  $form['cloud_context'] = array(
    '#type' => 'hidden',
    '#value' => $cloud_context,
  );

  $form['submit_buttons']['Create'] = array(
    '#type' => 'submit',
    '#value' => t('Create'),
  );
  $form['submit_buttons']['Cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}


/**
 *
 * @param  $form
 * @return
 */
function theme_aws_cloud_create_ebs_volume($form) {


  if (isset($form['form'])) {

    $form = $form['form'];
  }

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['Volume_details']['Name_label'     ] ),
      \Drupal::service("renderer")->render($form['Volume_details']['Name_text'      ] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['Volume_details']['Snapshot_label'] ),
      \Drupal::service("renderer")->render($form['Volume_details']['Snapshot_select'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['Volume_details']['zone_label'     ] ),
      \Drupal::service("renderer")->render($form['Volume_details']['zone_select'    ] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['Volume_details']['Size_label'     ] ),
      \Drupal::service("renderer")->render($form['Volume_details']['Size_text'      ] ),
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

  $form['fieldset_volume_info']['#children'] = $table;

  $output  = \Drupal::service("renderer")->render($form['fieldset_volume_info']);
  $output .= \Drupal::service("renderer")->render($form['cloud_context']);
  $output .= \Drupal::service("renderer")->render($form['submit_buttons']);

  $output .= drupal_render_children($form);

  return $output;
}


/**
 * Validate Volume function
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_create_ebs_volume_validate($form_id, $form_values) {
  $form_values     = $form_values['values'];
  $cloud_context   = $form_values['cloud_context'];
  $cloud_menu_path = cloud_get_menu_path($cloud_context );

  if ( $form_values['op'] == t('Create')) {
    if ( empty($form_values['Name_text'])) {
      form_set_error('Name_text', t('You must enter valid Name.'));
    }
    else {
      $query = _aws_cloud_get_volume($cloud_context);

      $query_args = array(
        ':nickname' => $form_values['Name_text'],
        ':cloud_type' => $cloud_context,
      );

      $result = db_query( $query, $query_args );
      
      foreach ($result as $db_obj) {
        $vol_id = $db_obj->volume_id;
        form_set_error('Name_text', t('Name is already used by a EBS-Volume.'));
        break;
      }
    }

    if (empty($form_values['Size_text']) ) {
      form_set_error('Size_text', t('You must enter Size'));
    }
  }
}



/**
 * Create Volume
 *
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_create_ebs_volume_submit($form_id, &$form_state) {

  $form_values = $form_state['values'];
  $cloud_context = $form_values['cloud_context'];
  $cloud_menu_path = cloud_get_menu_path($cloud_context);
  
  if ($form_values['op'] == t('Create')) {
    $result = aws_cloud_create_volume($cloud_context, $form_values['Name_text'], $form_values['Size_text'], $form_values['zone_select'], $form_values['Snapshot_select']);
    if ($result) {
      drupal_set_message(t('Creating Volume.'));
      $form_state['redirect'] = $cloud_menu_path . '/ebs_volumes';
    }
    else {
      drupal_set_message(t('Unable to save Volume: @name', array('@name' => $form_values['Name_text'])), 'error');
    }
  }
  $form_state['redirect'] = $cloud_menu_path . '/ebs_volumes';
}


/**
 * Delete a EBS volume
 * @param  $cloud_context
 * @return
 */
function aws_cloud_delete_ebs_volume($cloud_context) {

  $vol_id = isset( $_REQUEST['vol_id'] ) ? $_REQUEST['vol_id'] : FALSE;
  $cloud_menu_path = cloud_get_menu_path($cloud_context);
  
  if (empty($vol_id)) {
    drupal_goto($cloud_menu_path . '/ebs_volumes');
  } 
  else {
    $result = aws_cloud_delete_volume($cloud_context, $vol_id);
    if ($result) {
      drupal_set_message(t('Volume deleted successfully: @volume_id', array('@volume_id' => $vol_id)));
    }
    else {
      drupal_set_message(\Drupal\Component\Utility\SafeMarkup::checkPlain( t('Unable to delete the Volume: @volume_id', array('@volume_id' => $vol_id) ) ), 'error');
    }
  }
  drupal_goto($cloud_menu_path . '/ebs_volumes');
}


/**
 * Detach a volume from an instance
 * @param  $cloud_context
 * @return
 */
function aws_cloud_detach_ebs_volume($cloud_context) {

  $vol_id = isset( $_REQUEST['vol_id'] ) ? $_REQUEST['vol_id'] : FALSE;
  $cloud_menu_path = cloud_get_menu_path($cloud_context );
  
  if (empty($vol_id)) {
    drupal_goto($cloud_menu_path . '/ebs_volumes');
  } 
  else {
    $result = aws_cloud_detach_volume($cloud_context, $vol_id);
    if ($result) {
      drupal_set_message(t('Detaching Volume: @vol_id. This may take some time.', array('@vol_id' => $vol_id)));
    }
    else {
      drupal_set_message(t('There was a problem while detaching the volume: @vol_id', array('@vol_id' => $vol_id)), 'error');
    }
  }
  drupal_goto($cloud_menu_path . '/ebs_volumes');
}

/**
 * Display Volume details
 *
 * @param  $form_submit
 *           form object
 * @param  $cloud_context
 *              sub-cloud
 * @return
 */
function aws_cloud_display_ebs_volume($form, $form_submit = '', $cloud_context) {

  $vol_id = isset( $_REQUEST['vol_id'] ) ? $_REQUEST['vol_id'] : FALSE;
  if ( empty($vol_id) ) {
    $cloud_menu_path = cloud_get_menu_path($cloud_context );
    drupal_goto($cloud_menu_path . '/ebs_volumes');
    return;
  }

  $cloud_menu_path = cloud_get_menu_path($cloud_context );

  if (empty($vol_id) || strlen($vol_id) == 0  ) {
    drupal_goto( $cloud_menu_path . '/ebs_volumes' );
    return;
  }

  $form['volume_id'           ] = array(
    '#type' => 'hidden'  ,
    '#value' => $vol_id,
  );
  $form['cloud_context'       ] = array(
    '#type' => 'hidden'  ,
    '#value' => $cloud_context,
  );
  $form['fieldset_volume_info'] = array(
    '#type' => 'fieldset',
    '#title' => t('Volume Info'),
  );
  $form['instance_locked'     ] = array(
    '#type' => 'hidden'  ,
    '#value' => '',
  );

  $vol_name = $vol_id;

  $query = _aws_cloud_get_volumes_query($cloud_context);
  $query_args = array(
    ':volume_id' => $vol_id,
    ':cloud_type' => $cloud_context,
  );

  $result          = db_query( $query, $query_args );
  $instance_id     = '';
  $button_disabled = '';
  $lock_status     = FALSE;

  $cnt = 0;

  foreach ($result as $key) {

    $form['vol_name']['vol_name_label'] = array(
      '#type' => 'item',
      '#title' => t('Nickname'),
      '#required' => TRUE,
    );
    $vol_name = $key->nickname;
    $form['vol_name_details'] = array(
      '#prefix' => '<div class="container-inline">',
      '#suffix' => '</div>',
    );

    if (\Drupal::currentUser()->hasPermission($cloud_context . ' create volume')) {

      $form['vol_name_details']['vol_name_text'] = array(
        '#type' => 'textfield',
        '#default_value' => $key->nickname,
      );
    }
    else {

      $form['vol_name_details']['vol_name_text'] = array(
        '#type' => 'textfield',
        '#default_value' => $key->nickname,
        '#disabled' => TRUE,
      );
    }

    if (\Drupal::currentUser()->hasPermission($cloud_context . ' create volume')) {
      $form['vol_name_details']['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Update'),
      );
    }

    $form['vol_details']['vol_label'    ] = array(
      '#type' => 'item',
      '#title' => t('ID'),
    );
    $form['vol_details']['vol_text'     ] = array('#markup' => t( $key->volume_id ));

    $form['vol_details']['vol_Size'     ] = array(
      '#type' => 'item',
      '#title' => t('Size'),
    );
    $form['vol_details']['vol_txt_size'] = array('#markup' => t( $key->vol_size ));

    $form['vol_details']['vol_Zone'     ] = array(
      '#type' => 'item',
      '#title' => t('Availability Zone'),
    );
    $form['vol_details']['vol_text_zone'] = array('#markup' => t( $key->vol_zone ));

    $form['vol_details']['vol_Server'   ] = array(
      '#type' => 'item',
      '#title' => t('Instance'),
    );
    
    if (empty($key->instance_id) === FALSE ) {
      $instance_id = $key->instance_id;
      $lock_status = _aws_cloud_get_lock_status_by_id_query($cloud_context, $instance_id);
      $lock_icon = '';
      if (empty($lock_status) === FALSE && $lock_status === 'Lock'  ) {

        $button_disabled = TRUE;
        $form['instance_locked']['#value'] = TRUE;
      }
      // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['vol_details']['vol_text_server'] = array('#markup' => l( $key->instance_nickname, $cloud_menu_path . '/instances/describe/' . $key->instance_id . '/info', array('query' => array('instance_id' => urlencode($key->instance_id)))) . $lock_icon);

    }
    else {
      $form['vol_details']['vol_text_server'] = array('#markup' => t( '' ));
    }

    $form['vol_details']['vol_Status'       ] = array(
      '#type' => 'item',
      '#title' => t('Status'),
    );
    $form['vol_details']['vol_text_status'  ] = array('#markup' => t( $key->status   ));

    $form['vol_details']['vol_Device'       ] = array(
      '#type' => 'item',
      '#title' => t('Device'),
    );
    $form['vol_details']['vol_text_device'  ] = array('#markup' => t( $key->device   ));

    $form['vol_details']['vol_Created'      ] = array(
      '#type' => 'item',
      '#title' => t('Created'),
    );
    $vol_text_created = '' ;
    if (empty($key->created) === FALSE) {

      $vol_text_created = format_date(strtotime($key->created), 'short');
    }
    $form['vol_details']['vol_text_created'] = array('#markup' => $vol_text_created);
    $cnt = 1;
  }

  if ($cnt == 0) {
    drupal_set_message(t('Volume not present'),    'error' );
    drupal_goto( $cloud_menu_path . '/ebs_volumes' );
    return;
  }

  $form['submit_buttons'] = array(
    '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
    '#type' => 'fieldset',
    '#suffix' => '</div></div>',
  );

  // Create Snapshot Button
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' create snapshot') ) {
    $form['submit_buttons']['Create Snapshot'] = array(
      '#type' => 'submit',
      '#value' => t('Create Snapshot'),
    );
  }

  // Delete Button
  if (empty($instance_id) && \Drupal::currentUser()->hasPermission($cloud_context . ' delete volume') ) {
    $confirm_msg = cloud_get_messagebox('Are you sure you want to delete the Volume "' . $vol_name . '" ?');
    $form['submit_buttons']['Delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#attributes' => array('onclick' => $confirm_msg),
    );
  }

  // Detach Button
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' detach volume') && empty($instance_id) === FALSE ) {
    $confirm_msg = cloud_get_messagebox('Are you sure you want to detach the Volume "' . $vol_name . '" ?');
    $form['submit_buttons']['Detach'] = array(
      '#type' => 'submit',
      '#value' => t('Detach'),
      '#attributes' => array('onclick' => $confirm_msg),
      '#disabled' => $button_disabled,
    );
  }

  // Volumes List
  $form['submit_buttons']['List Volumes'] = array(
    '#type' => 'submit',
    '#value' => t('List Volumes'),
  );

  return $form;
}


/**
 * Theme function for Volume Edit/View Page
 * @param  $form
 * @return
 */
function theme_aws_cloud_display_ebs_volume($form) {
  $form = $form['form'];
  $cloud_context = $form['cloud_context']['#value'];

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['vol_name'        ]['vol_name_label']    ),
      \Drupal::service("renderer")->render($form['vol_name_details']                      )
        . \Drupal::service("renderer")->render($form['vol_name_details']['vol_name_text'])
        . \Drupal::service("renderer")->render($form['vol_name_details']['submit'        ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_label']       ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_text']       ),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_Size']        ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_txt_size']    ),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_Zone']        ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_text_zone']   ),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_Server']      ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_text_server'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_Status']      ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_text_status'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_Device'     ] ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_text_device'] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['vol_details']['vol_Created'     ] ),
      \Drupal::service("renderer")->render($form['vol_details']['vol_text_created'] ),
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

  $form['fieldset_volume_info']['#children'] = $table;

  $output  = \Drupal::service("renderer")->render($form['submit_buttons'      ]);
  $output .= \Drupal::service("renderer")->render($form['fieldset_volume_info']);
  $output .= \Drupal::service("renderer")->render($form['cloud_context'       ]);
  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Function for volume validation 
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_display_ebs_volume_validate($form_id, $form_values) {
  $form_values = $form_values['values'];
  if ($form_values['op'] == t('Update')) {
    $vol_name = trim( $form_values['vol_name_text'] );
    if ( empty($vol_name) == TRUE) {
      form_set_error('vol_name_text', t('Please specify Volume Nickname'));
    }
  }
}

/**
 * Submit form to update the Volume Nickname
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_display_ebs_volume_submit($form_id, &$form_state) {

  $form_values     =& $form_state['values'];
  $cloud_context   = $form_values['cloud_context'];
  $cloud_menu_path = cloud_get_menu_path($cloud_context);

  if ($form_values['op'] == 'Update') {
    _aws_cloud_update_attach_volume($form_values['volume_id'], $form_values['vol_name_text']);

    // User Activity Log
    cloud_audit_user_activity( 
      array(
        'type' => 'user_activity',
        'message' => t('Volume has been modified: @volume_id', array('@volume_id' => $form_values['volume_id'])),
        'link' => '',
      )
    );

    drupal_set_message(t('Updated nickname: @volume_name (@volume_id)', array(
      '@volume_name' => $form_values['vol_name_text'],
      '@volume_id' => $form_values['volume_id'],
    )));
    // @FIXME
// url() expects a route name or an external URI.
// $form_state['redirect'] = url($cloud_menu_path . '/ebs_volumes/describe', array('query' => array('vol_id' => $form_values['volume_id']), 'absolute' => TRUE));

  }
  elseif ($form_values['op'] == 'Create Snapshot') {
    // @FIXME
// url() expects a route name or an external URI.
// $form_state['redirect'] = url($cloud_menu_path . '/ebs_volumes/create_snapshot', array('query' => array('vol_id' => $form_values['volume_id'], 'volume_name' => $form_values['vol_name_text']), 'absolute' => TRUE));

  }
  elseif ($form_values['op'] == 'Delete') {
    // @FIXME
// url() expects a route name or an external URI.
// $form_state['redirect'] = url($cloud_menu_path . '/ebs_volumes/delete', array('query' => array('vol_id' => $form_values['volume_id']), 'absolute' => TRUE));

  }
  elseif ($form_values['op'] == 'Detach') {
    // @FIXME
// url() expects a route name or an external URI.
// $form_state['redirect'] = url($cloud_menu_path . '/ebs_volumes/detach', array('query' => array('vol_id' => $form_values['volume_id'], 'destination' => current_path()), 'absolute' => TRUE));

  }
}

/**
 *  Create Snapshot
 *
 * @param  $form_submit
 *              Form object
 * @param  $cloud_context
 *              sub-cloud
 * @return
 */
function aws_cloud_create_ebs_volume_snapshot($form, $form_submit = '', $cloud_context) {

  $vol_id = isset( $_REQUEST['vol_id'] )
    ?  $_REQUEST['vol_id']
    :  FALSE;

  if ( empty($vol_id) ) {

    $cloud_menu_path = cloud_get_menu_path($cloud_context );
    drupal_goto($cloud_menu_path . '/ebs_volumes');
    return;
  }

  $volume_name = isset($_REQUEST['volume_name'])
    ?  $_REQUEST['volume_name']
    :  '';

  $form['cloud_context'] = array(
    '#type' => 'hidden',
    '#value' => $cloud_context,
  );

  $form['SSName_details']['SSName_label'] = array(
    '#type' => 'item',
    '#title' => t('Nickname'),
  );
  $form['SSName_details']['SSName_text'  ] = array('#type' => 'textfield');

  $form['SSName_details']['SSVol_label'  ] = array(
    '#type' => 'item',
    '#title' => t('Volume'),
  );
  $form['SSName_details']['SSVol_text'   ] = array('#markup' => \Drupal\Component\Utility\Xss::filter( $volume_name    ));

  $form['SSName_details']['SSVolId_label'] = array(
    '#type' => 'item',
    '#title' => t('Volume ID'),
  );
  $form['SSName_details']['SSVolId_text'] = array('#markup' => t( $vol_id));

  $form['SSName_details']['SSDesc_label'] = array(
    '#type' => 'item',
    '#title' => t('Description'),
  );
  $form['SSName_details']['SSDesc_text'  ] = array('#type' => 'textarea');

  $form['create_ss_submit_buttons'] = array(
    '#type' => 'fieldset',
    '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
    '#suffix' => '</div></div>',
  );

  $form['create_ss_submit_buttons']['Create'] = array(
    '#type' => 'submit',
    '#value' => t('Create'),
  );
  $form['create_ss_submit_buttons']['Cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  $form['volume_id']  = array(
    '#type' => 'hidden',
    '#value' => $vol_id,
  );

  return $form;
}


/**
 * Theme function for create snapshot from volume form
 * @param  $form
 * @return
 */
function theme_aws_cloud_create_ebs_volume_snapshot($form) {


  if (isset($form['form'])) {

    $form = $form['form'];
  }

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['SSName_details']['SSName_label']),
      \Drupal::service("renderer")->render($form['SSName_details']['SSName_text'  ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['SSName_details']['SSVol_label'  ]),
      \Drupal::service("renderer")->render($form['SSName_details']['SSVol_text'   ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['SSName_details']['SSVolId_label']),
      \Drupal::service("renderer")->render($form['SSName_details']['SSVolId_text']),
    ),
    array(
      \Drupal::service("renderer")->render($form['SSName_details']['SSDesc_label']),
      \Drupal::service("renderer")->render($form['SSName_details']['SSDesc_text'  ]),
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
// $output  = theme('table', array('header' => NULL, 'rows' => $rows));

  $output .= \Drupal::service("renderer")->render($form['create_ss_submit_buttons'] );
  $output .= \Drupal::service("renderer")->render($form['volume_id'              ] );
  $output .= \Drupal::service("renderer")->render($form['cloud_context'          ] );
  $output .= drupal_render_children($form);

  return $output;
}

/**
 *
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_create_ebs_volume_snapshot_validate($form_id, $form_values) {
  $form_values   = $form_values['values'];
  $cloud_context = $form_values['cloud_context'];
  if ($form_values['op'] == t('Create')) {
    if (empty($form_values['SSName_text'])) {
      form_set_error('SSName_text', t('You must enter valid Snap Shot name'));
    }
    else {
      if (_aws_cloud_get_count_name_snapshots_query($cloud_context, $form_values['SSName_text']) > 0) {
        form_set_error('SSName_text', t('Name is already used by a EBS-Snapshot.'));
      }
    }
  }
}

/**
 * Submit function handling creating a volume snapshot
 * @param  $form_id
 * @param  $form_values
 * @return
 */
function aws_cloud_create_ebs_volume_snapshot_submit($form_id, &$form_state) {
  $form_values     = $form_state['values'];
  $cloud_context   = $form_values['cloud_context'];
  $cloud_menu_path = cloud_get_menu_path($cloud_context );

  if ($form_values['op'] == 'Create') {
    $result = aws_cloud_save_snapshot($cloud_context, $form_values['volume_id'], $form_values['SSName_text'], $form_values['SSDesc_text']);
    if ($result) {
      drupal_set_message(t('Initiated create Snapshot request. This may take some time.'));
    }
    else {
      drupal_set_message(t('Unable to create Snapshot'), 'error');
    }
  }
  $form_state['redirect'] = $cloud_menu_path . '/ebs_snapshots';
}
