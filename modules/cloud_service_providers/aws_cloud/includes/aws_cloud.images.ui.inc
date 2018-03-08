<?php

/**
 * @file
 * UI functions for Images
 *
 */

/**
 * Function to render the image listing page
 */
function aws_cloud_display_images_list($form_details, $form_state, $cloud_context, $owner_flag) {

  // @FIXME
// The Assets API has totally changed. CSS, JavaScript, and libraries are now
// attached directly to render arrays using the #attached property.
// 
// 
// @see https://www.drupal.org/node/2169605
// @see https://www.drupal.org/node/2408597
// drupal_add_js(cloud_get_module_base() . 'js/cloud.js');

  $cloud_menu_path  = cloud_get_menu_path($cloud_context);
  
  $column      = 'image_nickname';
  $query_args  = array();
  $options     = array(
    t('NickName'    ),
    t('Image ID'    ),
    t('Architecture'),
    t('Location'    ),
    t('Ownership'   ),
  );

  $filter     = isset($_REQUEST['filter'   ]) ? $_REQUEST['filter'   ] : '';
  $filter_col = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : 0  ; // default: Nickname
  $filter     = trim($filter);

  $sql_col   = 'image_nickname';

  if ( $filter_col == 0 ) {
    $column  = 'Image Name'     ;
    $sql_col = 'image_nickname';
  }
  elseif ($filter_col == 1 ) {
    $column  = 'Image ID'       ;
    $sql_col = 'image_id'       ;
  }
  elseif ($filter_col == 2 ) {
    $column  = 'Architecture'   ;
    $sql_col = 'architecture'   ;
  }
  elseif ($filter_col == 3 ) {
    $column  = 'Location'       ;
    $sql_col = 'image_location';
  }
  elseif ($filter_col == 4 ) {
    $column  = 'Ownership'      ;
    $sql_col = 'imageowner_id'  ;
  }
  elseif ($filter_col == 5 ) {
    $column  = 'Launch'         ;
    $sql_col = 'image_id'       ;
  }

  if ( isset($filter) ) {

    $query_args[] = $sql_col;
    $query_args[] = $filter  ;
  }

  $form['options'] = array(
    '#prefix' => '<div class="container-inline">',
    '#type' => 'fieldset',
    '#suffix' => '</div>',
  );

  $image_count = _aws_cloud_get_images_list_count($cloud_context);
  $filter_disabled = '';
  if ( $image_count < 2 ) {
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

  $form['header'] = array(
    '#type' => 'value',
    '#value' => array(
      array('data' => t('Nickname'), 'field' => 'image_nickname', array('class' => 'nickname-column')),
      array('data' => t('ID'), 'field' => 'image_id'),
      array('data' => t('Architecture'), 'field' => 'architecture'),
      array('data' => t('Location'), 'field' => 'image_location'),
      array('data' => t('Ownership'), 'field' => 'imageowner_id'),
      array('data' => t('Action'), 'class' => array('action-column')),
    ),
  );
  
  if ($owner_flag === 'all' ) {
    $query = _aws_cloud_get_describe_images_query($cloud_context);
    $query->condition('c.cloud_type', $cloud_context, '=');
    $query->condition($sql_col, '%' . $filter . '%', 'like');
  }
  else {
    $cloud_user_id = cloud_get_info( $cloud_context, 'user_id' )  ;
    $query         = _aws_cloud_get_describe_owner_images_query($cloud_context);
    $query
      ->condition('imageowner_id', $cloud_user_id, '=')
      ->condition('cloud_type', $cloud_context, '=')
      ->condition($sql_col, '%' . $filter . '%', 'like');
  }

  $query = $query
      ->extend('TableSort')
      ->extend('PagerDefault')
      ->orderByHeader($form['header']['#value'])
      ->limit(AWS_CLOUD_PAGER_LIMIT);

  $result = $query->execute();

  foreach ($result as $image) {
    $form['Nickname'][$image->image_id] = array(
      '#prefix' => '<span class="anchor">',
      '#markup' => t($image->image_nickname),
      '#suffix' => '</span>',
    );
    $form['ID'][$image->image_id] = array('#markup' => t($image->image_id));
    $form['Architecture'][$image->image_id] = array('#markup' => t($image->architecture));
    $form['Location'][$image->image_id] = array('#markup' => t($image->image_location ));
    $form['Ownership'][$image->image_id] = array('#markup' => t($image->imageowner_id));
    $form['Image_type'][$image->image_id] = array('#value' => t($image->image_type), '#type' => 'hidden');
    $form['Actions'][$image->image_id] = array('#type' => 'markup', '#markup' => aws_cloud_get_images_action($cloud_context, array('image_type' => $image->image_type, 'image_id' => $image->image_id, 'image_name' => $image->image_nickname)));
  }

  $form['cloud_context'] = array(
    '#type' => 'hidden',
    '#value' => $cloud_context,
  );
  
  // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['refresh_page'] = array(
//     '#type' => 'item',
//     '#prefix' => '<div id="link_reload" align="right">',
//     '#suffix' => '</div>',
//     '#markup' => l( t('- Refresh Page -'), $cloud_menu_path . '/getimagedata', array('query' => drupal_get_destination())),
//   );

  
  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $form['pager'] = array('#value' => theme('pager', array('tags' => NULL, 'element' => 0)));

  $form_state['#redirect']  = FALSE;

  return $form;
}


/**
 *  Theme the Image Listing Page
 */
function theme_aws_cloud_display_images_list($form) {
  if (isset($form['form'])) { // drupal 7 passed form as an array 
    $form = $form['form'];
  }
  $output = \Drupal::service("renderer")->render($form['options']);
  
  $rows = array();
  if (isset($form['Nickname'])) {
    foreach (\Drupal\Core\Render\Element::children($form['Nickname']) as $key) {
      $rows[] = array(
        array('data' => \Drupal::service("renderer")->render($form['Nickname'][$key]), 'class' => array('nickname-column')),
        \Drupal::service("renderer")->render($form['ID'][$key]),
        \Drupal::service("renderer")->render($form['Architecture'][$key]),
        \Drupal::service("renderer")->render($form['Location'][$key]),
        \Drupal::service("renderer")->render($form['Ownership'][$key]),
        array(
          'data' => \Drupal::service("renderer")->render($form['Actions'][$key]),
          'class' => array('action-column'),
        ),
      );
    }
  }
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
// $output .= theme('pager', array('tags' => array()));

  $output .= \Drupal::service("renderer")->render($form['refresh_page']);

  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Generate action links for a given image.  Image_info is an associative 
 * array containing image_type, image_id, and image_name. 
 */
function aws_cloud_get_images_action($cloud_context, $image_info) {
  $action_data = array();
  
  if (\Drupal::currentUser()->hasPermission($cloud_context . ' launch instance') ) {
    $image_name = $image_info['image_name'] . ' (' . $image_info['image_id'] . ')';
    $path = CLOUD_SERVER_TEMPLATES_PATH . '/' . $cloud_context . '/create';
    $action_data[] = cloud_display_action('images/icon_play.png', t('Template'), $path,  array('query' => array('image_type' => $image_info['image_type'] , 'image_id_select' => $image_name), 'html' => TRUE));
  }
  
  $user_actions = \Drupal::moduleHandler()->invokeAll('aws_cloud_images_action_data', [$image_info]);
  $action_data = array_merge($action_data, $user_actions);
  return implode($action_data);
}

/**
 * Form sumbit for image listing page
 */
function aws_cloud_display_images_list_submit($form_id, &$form_state) {
  $values =& $form_state['values'];

  if ($values['op'] == t('Apply')) {
    $param_arr = array(
      'filter' => $values['filter'],
      'operation' => $values['operation'],
    );
    $form_state['redirect'] = array(\Drupal\Core\Url::fromRoute("<current>")->toString(), array('query' => $param_arr));
  }
}


/**
 * @todo: move to main lib file
 * @param   $cloud_context
 * @return
 */
function _aws_cloud_get_instances($cloud_context) {

  $instance_options = array();
  $result  = _aws_cloud_get_instance_names($cloud_context);

  foreach ($result as $instance) {

    $instance_options[$instance->instance_id] = $instance->instance_nickname;
  }
  asort($instance_options);
  return $instance_options;
}

/**
 * Form for creating an image from a backup 
 */
function aws_cloud_display_backup_instance_form($form, $form_state, $cloud_context, $instance_id) {
  // store the instance_id
  $form = array();
  $instance = _aws_cloud_get_instance_details_by_id($cloud_context, $instance_id);
  
  if ($instance) {
    $form['instance_id'] = array(
      '#type' => 'value',
      '#value' => $instance_id,
    );
    $form['cloud_context'] = array(
      '#type' => 'value',
      '#value' => $cloud_context,
    );
    $form['instance_name'] = array(
      '#type' => 'item',
      '#markup' => $instance['name'],
    );
    $form['backup_name'] = array(
      '#type' => 'textfield',
    );
    $form['description'] = array(
      '#type' => 'textarea',
    );
    $form['no_reboot'] = array(
      '#type' => 'checkbox',
      '#default_value' => FALSE,
      '#description' => t('When checked, Amazon EC2 will not shut down the instance before creating the image.<br>When this option is used, file system integrity on the created image cannot be guaranteed.')
    );
    $form['buttons'] = array(
      '#type' => 'fieldset',
    );
    $form['buttons']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Create'),
    );
    $form['buttons']['cancel'] = array(
      '#type' => 'submit',
      '#value' => t('Cancel'),
    );
  }
  return $form;
}

/**
 * Theme for backup image
 */
function theme_aws_cloud_display_backup_instance_form($form) {
  $form = $form['form'];
  $rows = array(
    array(
      array('data' => '<label>' . t('Instance to Backup') . '</label>', 'class' => array('form-item', 'form-type-item')),
      \Drupal::service("renderer")->render($form['instance_name']),
    ),
    array(
      array('data' => '<label>' . t('Backup Name') . '</label>', 'class' => array('form-item', 'form-type-item')),
      \Drupal::service("renderer")->render($form['backup_name']),
    ),
    array(
      array('data' => '<label>' . t('Description') . '</label>', 'class' => array('form-item', 'form-type-item')),
      \Drupal::service("renderer")->render($form['description']),
    ),
    array(
      array('data' => '<label>' . t('No Reboot') . '</label>', 'class' => array('form-item', 'form-type-item')),
      \Drupal::service("renderer")->render($form['no_reboot']),
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

  $output .= \Drupal::service("renderer")->render($form['buttons']);
  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Validate function for backup instance 
 */
function aws_cloud_display_backup_instance_form_validate($form, $form_values) {
  if ($form_values['values']['op'] == t('Create') && empty($form_values['values']['backup_name'])) {
    form_set_error('backup_name', t('Please provide Backup Name'));
  }
}

/**
 * Submit function
 */
function aws_cloud_display_backup_instance_form_submit($form, &$form_state) {
  $cloud_context = $form_state['values']['cloud_context'];
  $instance_id = $form_state['values']['instance_id'];  
  $cloud_menu_path = cloud_get_menu_path($cloud_context);
  if ($form_state['values']['op'] == t('Create')) {
    // take the backup name, message, reboot flag make an api call
    $backup_name = $form_state['values']['backup_name'];
    $no_reboot = $form_state['values']['no_reboot'];
    $description = $form_state['values']['description'];  
    aws_cloud_create_image($cloud_context, $instance_id, $backup_name, $description, $no_reboot);
  }
  $form_state['redirect'] = $cloud_menu_path . '/instances/describe/' . $instance_id . '/info';
}
