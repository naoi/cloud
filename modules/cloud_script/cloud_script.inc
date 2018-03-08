<?php

/**
 * @file
 * Provides scripting feature such as bash, Perl and etc (Mainly bash scripts).
 * Works with Cloud and Server Templates module.
 *
 */

/**
 * scripting callback function
 */
function _cloud_script_list($module) {
  return \Drupal::formBuilder()->getForm('cloud_script_list');
}

/**
 * Returns a form with listing of scripts
 * Build a form including table header and table body
 * @param $form_submit
 *        This is the form-submit submitted by list scripts form
 * @return return a form
 */
function cloud_script_list($form, $form_submit) {

  // @FIXME
// The Assets API has totally changed. CSS, JavaScript, and libraries are now
// attached directly to render arrays using the #attached property.
// 
// 
// @see https://www.drupal.org/node/2169605
// @see https://www.drupal.org/node/2408597
// drupal_add_js(cloud_get_module_base() . 'js/cloud.js');


  $column = 'name';

  $form['options'] = array(
    '#prefix' => '<div class="container-inline">',
    '#type' => 'fieldset',
    '#suffix' => '</div>',
  );

  $options = array(
    t('Nickname' ),
    t('Type'     ),
  );

  $filter     = cloud_get_filter_value( $form_submit, 'filter'   );
  $filter     = trim($filter);

  $filter_col = cloud_get_filter_value( $form_submit, 'operation');
  $filter_col = isset($filter_col) && $filter_col ? $filter_col : 0; // default: Script Name

  if ( $filter_col == 0 ) {
    $column  = 'Script Name';
    $sql_col = 'name'        ;
  }
  elseif ($filter_col == 1 ) {
    $column  = 'Type';
    $sql_col = 'type';
  }
  elseif ($filter_col == 2 ) {
    $column  = 'Packages';
    $sql_col = 'packages';
  }

  if ( isset($filter) ) {
    $query_args[] = $sql_col;
    $query_args[] = $filter  ;
  }
  else {
    $filter = ' 1 ';
    $query_args[] = ' ';
  }

  $script_count = _cloud_script_get_count_db();
  $filter_disabled = '';
  if ( $script_count < 2 ) {

    $filter_disabled = TRUE;
  }

  $form['options']['label'    ] = array(
    '#type' => 'item'     ,
    '#title' => t('Filter'),
  );
  $form['options']['operation'] = array(
    '#type' => 'select'   ,
    '#options' => $options  ,
    '#default_value' => $filter_col,
    '#disabled' => $filter_disabled,
  );
  $form['options']['filter'   ] = array(
    '#type' => 'textfield',
    '#size' => 40        ,
    '#default_value' => $filter    ,
    '#disabled' => $filter_disabled,
  );
  $form['options']['submit'   ] = array(
    '#type' => 'submit'   ,
    '#value' => t('Apply')                                 ,
    '#disabled' => $filter_disabled,
  );
  if (\Drupal::currentUser()->hasPermission('create script') ) {
    $form['options']['script'] = array(
      '#type' => 'submit'   ,
      '#value' => t('Create'),
    );
  }

  $form['header'] = array(
    '#type' => 'value',
    '#markup' => array(
      array(
        'data' => t('Nickname'),
        'field' => 'name',
        'sort' => 'asc',
        'class' => array('nickname-column'),
      ),
      array(
        'data' => t('Type'),
        'field' => 'type',
      ),
      array(
        'data' => t('Packages'),
        'field' => 'packages',
      ),
      array(
        'data' => t('Inputs'),
        'field' => 'inputs',
      ),
      array(
        'data' => t('Created'),
        'field' => 'created',
      ),
      array(
        'data' => t('Updated'),
        'field' => 'updated',
      ),
      array(
        'data' => t('Action'),
        'class' => 'action-column',
      ),
    ),
  );

  $query  = _cloud_script_get_scripts();
  $query = $query
    ->extend('PagerDefault')
    ->extend('TableSort')
    ->orderByHeader($form['header']['#markup']);

  $query
    ->condition($query_args[0], '%%' . $query_args[1] . '%%', 'like')
    ->fields('a')
    ->limit(CLOUD_SCRIPTING_PAGER_LIMIT);

  $result = $query->execute();

  $scripting_options  = cloud_server_templates_get_scripting_options();
  foreach ($result as $scripts_obj) {
    // @FIXME
// l() expects a Url object, created from a route name or external URI.
// $form['Name'    ][$scripts_obj->script_id] = array('#markup' => l( $scripts_obj->name, CLOUD_SCRIPTING_PATH . '/describe/' . $scripts_obj->script_id . '/info',    array('query' => array('id' => urlencode($scripts_obj->script_id))) ));
 //,  array(),   'id=' . urlencode($scripts_obj->id) ,  NULL,  FALSE,  TRUE ) ) );
    $form['Type'    ][$scripts_obj->script_id] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $scripting_options['CLOUD_SCRIPTING_TYPE_OPTIONS'][$scripts_obj->type] ));
    $form['Packages'][$scripts_obj->script_id] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $scripts_obj->packages ));
    $form['Inputs'  ][$scripts_obj->script_id] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $scripts_obj->inputs   ));
    $form['Created'][$scripts_obj->script_id] = array('#markup' => format_date(strtotime($scripts_obj->created), 'short'));
    $form['Updated'][$scripts_obj->script_id] = array('#markup' => format_date(strtotime($scripts_obj->updated), 'short'));
    $form['Actions'][$scripts_obj->script_id] = array('#markup' => cloud_script_action(array('script_id' => $scripts_obj->script_id, 'script_name' => $scripts_obj->name)));
  }

  $form_state['#redirect'] = FALSE;
  return $form;
}


/**
 * Theme function for cloud script listing page
 */
function theme_cloud_script_list($variables) {
  $form = $variables['form'];
  $rows = array();
  if (!empty($form['Name'])) {
    foreach (\Drupal\Core\Render\Element::children($form['Name']) as $key) {
      $rows[] = array(
        array(
          'data' => \Drupal::service("renderer")->render($form['Name'][$key]),
          'class' => array('nickname-column'),
        ),
        \Drupal::service("renderer")->render($form['Type'    ][$key]),
        \Drupal::service("renderer")->render($form['Packages'][$key]),
        \Drupal::service("renderer")->render($form['Inputs'  ][$key]),
        \Drupal::service("renderer")->render($form['Created'][$key]),
        \Drupal::service("renderer")->render($form['Updated'][$key]),
        array(
          'data' => \Drupal::service("renderer")->render($form['Actions'][$key]),
          'class' => array('action-column'),
        ),
      );
    }
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
// $output .= theme('table', array('header' => $form['header']['#markup'], 'rows' => $rows));

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
 * Function to render scripting actions links 
 */
function cloud_script_action($scripting_info) {
  $action_data = array();

  if (\Drupal::currentUser()->hasPermission('delete script')) {
    $prop['onclick'] = cloud_get_messagebox('Are you sure you want to delete the Script "' . $scripting_info['script_name'] . '" ?');
    $action_data[] = cloud_display_action(
      'images/icon_delete.png',
      t('Delete'),
      CLOUD_SCRIPTING_PATH . '/' . urlencode($scripting_info['script_id']) . '/delete',
      array('query' => array('id' => urlencode($scripting_info['script_id'])), 'html' => TRUE), $prop['onclick']
    );
  }
  if (\Drupal::currentUser()->hasPermission('edit script')) {
    $action_data[] = cloud_display_action(
      'images/icon_clear.png',
      t('Edit'),
      CLOUD_SCRIPTING_PATH . '/' . urlencode($scripting_info['script_id']) . '/edit',
      array('query' => array('id' => urlencode($scripting_info['script_id'])), 'html' => TRUE)
    );
  }
  
  // allow other modules to add action icons
  $user_actions = \Drupal::moduleHandler()->invokeAll('cloud_script_action_data', [$scripting_info]);
  $action_data = array_merge($action_data, $user_actions);
  return implode($action_data);
}

/**
 * Function for submitting cloud script listing page
 */
function cloud_script_list_submit($form_id, &$form_state) {
  $form_values =& $form_state['values'];
  if ($form_values['op'] == t('Create')) {
    $form_state['redirect'] = CLOUD_SCRIPTING_PATH . '/create';
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
 * Form function to display Script details
 */
function cloud_script_desc_info($form, $form_submit = '', $script_id = '') {

  if (empty($script_id) || strlen($script_id) == 0  ) {
    drupal_goto( CLOUD_SCRIPTING_PATH . '/list');
    return;
  }

  $query = _cloud_script_get_scripts();
  $query->condition('script_id', $script_id, '=');
  $result = $query
    ->execute()
    ->fetchObject();

  $form['fieldset_script_info'] = array(
    '#type' => 'fieldset',
    '#title' => t('Details'),
  );
  
  if ($result) {
    $script_name = $result->name;
    $form['name']['name_label'] = array('#type' => 'item', '#title' => t('Script Name'));
    $form['name']['name_text'] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain($result->name));

    $form['type']['type_label'] = array('#type' => 'item', '#title' => t('Script Type'));
    $form['type']['type_text'] = array('#markup' => t($result->type));

    $form['description']['description_label'] = array('#type' => 'item', '#title' => t('Description'));
    $form['description']['description_text'] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $result->description));

    $form['packages']['packages_label'] = array('#type' => 'item', '#title' => t('Packages'));
    $form['packages']['packages_text'] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain($result->packages));

    $form['cloud_inputs']['inputs_label'] = array('#type' => 'item', '#title' => t('Inputs'));
    $form['cloud_inputs']['inputs_text'] = array('#markup' => \Drupal\Component\Utility\SafeMarkup::checkPlain($result->inputs));

    $form['script']['script_label'] = array('#type' => 'item', '#title' => t('Script'));
    $form['script']['script_text'] = array('#type' => 'textarea', '#value' => $result->script_template, '#disabled' => 1);

    $form['created']['created_label'] = array('#type' => 'item', '#title' => t('Created'));
    $form['created']['created_text'] = array('#markup' => format_date(strtotime($result->created), 'short'));

    $form['updated']['updated_label'] = array('#type' => 'item', '#title' => t('Updated'));
    $form['updated']['updated_text'] = array('#markup' => format_date(strtotime($result->updated), 'short'));

    $form['Script']['id'] = array('#type' => 'value', '#value' => $result->script_id);

    $form['submit_buttons'] = array(
      '#type' => 'fieldset',
      '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
      '#suffix' => '</div></div>',
    );
    
    if (\Drupal::currentUser()->hasPermission('edit script')) {
      $form['submit_buttons']['Edit'] = array(
        '#type' => 'submit',
        '#value' => t('Edit'),
      );
    }
    
    if (\Drupal::currentUser()->hasPermission('delete script')) {
      $confirm_msg = cloud_get_messagebox('Are you sure you want to delete the Script "' . $script_name . '" ?');
      $onclick     = $confirm_msg;
      $form['submit_buttons']['Delete'] = array(
        '#type' => 'submit',
        '#value' => t('Delete'),
        '#attributes' => array('onclick' => $onclick),
      );
    }
    
    $form['submit_buttons']['List Scripts'] = array(
      '#type' => 'submit',
      '#value' => t('List Scripts'),
    );
  }
  return $form;
}

/**
 * Submit function for cloud scription details page 
 */
function cloud_script_desc_info_submit($form, &$form_state) {
  $op = $form_state['values']['op'];
  $script_id = $form_state['values']['id'];
  switch ($op) {
    case 'Edit':
      $form_state['redirect'] = CLOUD_SCRIPTING_PATH . '/' . $script_id . '/edit';
      break;
    case 'Delete':
      $form_state['redirect'] = CLOUD_SCRIPTING_PATH . '/' . $script_id . '/delete';
      break;
    default:
      $form_state['redirect'] = CLOUD_SCRIPTING_PATH . '/' . 'list';
      break;
  }
}

/**
 * Theme function for cloud script details page 
 */
function theme_cloud_script_desc_info($variables) {
  $form = $variables['form'];

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['name']['name_label']),
      \Drupal::service("renderer")->render($form['name']['name_text']),
    ),
    array(
      \Drupal::service("renderer")->render($form['type']['type_label']),
      \Drupal::service("renderer")->render($form['type']['type_text']),
    ),
    array(
      \Drupal::service("renderer")->render($form['description']['description_label']),
      \Drupal::service("renderer")->render($form['description']['description_text']),
    ),
    array(
      \Drupal::service("renderer")->render($form['packages']['packages_label'  ]),
      \Drupal::service("renderer")->render($form['packages']['packages_text'   ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['cloud_inputs']['inputs_label']),
      \Drupal::service("renderer")->render($form['cloud_inputs']['inputs_text']),
    ),
    array(
      \Drupal::service("renderer")->render($form['script']['script_label'      ]),
      \Drupal::service("renderer")->render($form['script']                      )
    . \Drupal::service("renderer")->render($form['script']['script_text'       ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['created']['created_label'    ]),
      \Drupal::service("renderer")->render($form['created']['created_text'     ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['updated']['updated_label'    ]),
      \Drupal::service("renderer")->render($form['updated']['updated_text'     ]),
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

  $form['fieldset_script_info']['#children'] = $table;

  $output  = \Drupal::service("renderer")->render($form['submit_buttons'      ]);
  $output .= \Drupal::service("renderer")->render($form['fieldset_script_info']);
  $output .= \Drupal::service("renderer")->render($form['id'                  ]);
  $output .= drupal_render_children($form                        );

  return $output;
}


/**
 * Describe a script using script_id
 */
function cloud_script_desc_script($form_submit = '', $script_id = '') {

  if (empty($script_id) || strlen($script_id) == 0  ) {
    drupal_goto( CLOUD_SCRIPTING_PATH . '/list');
  }

  // @FIXME
// // @FIXME
// // The correct configuration object could not be determined. You'll need to
// // rewrite this call manually.
// variable_set( $user->uid . '_desc_info', $script_id );


  $query  = _cloud_script_get_scripts();
  $query->condition('script_id', $script_id, '=');
  $result = $query->execute();

  $count  = 0;
  foreach ($result as $key) {
    $form['packages'    ]['packages_label'] = array(
      '#type' => 'item',
      '#title' => t('Packages'),
    );
    $form['packages'    ]['packages_text'] = array('#value' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $key->packages));

    $form['cloud_inputs']['inputs_label'  ] = array(
      '#type' => 'item',
      '#title' => t('Inputs'),
    );
    $form['cloud_inputs']['inputs_text'   ] = array('#value' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $key->inputs));

    $form['script'      ]['script_label'  ] = array(
      '#type' => 'item',
      '#title' => t('Script'),
    );
    $form['script'      ]['script_text'   ] = array(
      '#type' => 'textarea',
      '#value' => \Drupal\Component\Utility\SafeMarkup::checkPlain( $key->script_template),
      '#disabled' => 1,
    );

    $form['Script'      ]['id'            ] = array(
      '#type' => 'hidden',
      '#value' => $key->script_id,
    );

    $count++;
  }

  if ( $count == 0 ) {
    drupal_goto( MODULE_PATH . '/list');
  }

  return $form;
}


/**
 * Theme the script details page
 * @see http://drupal.org/node/1354
 */
function theme_cloud_script_desc_script($variables) {
  $form = $variables['form'];

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['packages']['packages_label'  ]),
      \Drupal::service("renderer")->render($form['packages']['packages_text'   ]),
    ),
    array(
      \Drupal::service("renderer")->render($form['cloud_inputs']['inputs_label']),
      \Drupal::service("renderer")->render($form['cloud_inputs']['inputs_text']),
    ),
    array(
      \Drupal::service("renderer")->render($form['script']['script_label'      ]),
      \Drupal::service("renderer")->render($form['script']                      )
    . \Drupal::service("renderer")->render($form['script']['script_text'       ]),
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

  $output .= \Drupal::service("renderer")->render( $form['id'] );
  $output .= drupal_render_children($form);

  return $output;
}

/**
 * Delete a script
 */
function cloud_script_delete($script_id = '') {
  _cloud_script_delete($script_id);
  drupal_set_message(t('Script has been deleted successfully.'));
  drupal_goto(CLOUD_SCRIPTING_PATH . '/list');
}


/**
 * Function to create form for new script
 */
function cloud_script_create($form, $form_submit = '', $script_id = '') {

  $scripting_options = cloud_server_templates_get_scripting_options();
  $action = t('Add');
  // @FIXME
// drupal_set_title() has been removed. There are now a few ways to set the title
// dynamically, depending on the situation.
// 
// 
// @see https://www.drupal.org/node/2067859
// drupal_set_title(t('Add Script'));


  $form['fieldset_script_info'      ] = array('#type' => 'fieldset' /* '#title' => t('Script Info') */);

  $form['name'           ]['name_label'           ] = array(
    '#type' => 'item',
    '#title' => t('Name'),
  );
  $form['name'           ]['name_text'            ] = array('#type' => 'textfield');

  $form['description'    ]['description_label'    ] = array(
    '#type' => 'item',
    '#title' => t('Description'),
  );
  $form['description'    ]['description_text'     ] = array('#type' => 'textarea');

  $form['script_type'    ]['script_type_label'    ] = array(
    '#type' => 'item'        ,
    '#title' => t('Script Type'),
    '#description' => t('The field value takes Boot, Operational, and Termination<br />when a script should be executed.<br />Currently only "booting" time execution is supported.'),
  );
  $form['script_type'    ]['script_type_select'   ] = array(
    '#type' => 'select',
    '#options' => $scripting_options['CLOUD_SCRIPTING_TYPE_OPTIONS'],
  );

  $form['cloud_inputs'   ]['inputs_label'         ] = array(
    '#type' => 'item'  ,
    '#title' => t('Inputs'),
    '#description' => t('Use the inputs as variables under Script code below.<br />Enter comma separated values.  (e.g. MESSAGE, OUTPUT_FILE)<br />The input parameters are configurable;<br />the actual parameters can be settled in a template.'),
  );
  $form['cloud_inputs'   ]['inputs_text'          ] = array('#type' => 'textfield');

  $form['script_template']['script_template_label'] = array(
    '#type' => 'item'  ,
    '#title' => t('Script'),
    '#description' => t('Put a script template here.  (e.g. echo MESSAGE > OUTPUT_FILE)'),
  );
  $form['script_template']['script_template_text'] = array('#type' => 'textarea');

  if ($script_id) {
    // @FIXME
// drupal_set_title() has been removed. There are now a few ways to set the title
// dynamically, depending on the situation.
// 
// 
// @see https://www.drupal.org/node/2067859
// drupal_set_title(t('Edit Script'));

    $query      = _cloud_script_get_scripts();
    $query->condition('script_id', $script_id, '=');
    $result       = $query->execute();
    $scripts_obj    = $result->fetchObject();
    $action = t('Edit');
    $form['script_id'      ] = array(
      '#type' => 'hidden',
      '#value' => $script_id,
    );
    $form['inputs_text_old'      ] = array(
      '#type' => 'hidden',
      '#value' => $scripts_obj->inputs,
    );
    $form['name'           ]['name_text'           ]['#default_value'] = isset_variable($scripts_obj->name             );
    $form['description'    ]['description_text'    ]['#default_value'] = isset_variable($scripts_obj->description      );
    $form['script_type'    ]['script_type_select'  ]['#default_value'] = isset_variable($scripts_obj->type             );
    $form['packages'       ]['packages_text'       ]['#default_value'] = isset_variable($scripts_obj->packages         );
    $form['cloud_inputs'   ]['inputs_text'         ]['#default_value'] = isset_variable($scripts_obj->inputs           );
    $form['script_template']['script_template_text']['#default_value'] = isset_variable(($scripts_obj->script_template));
  }

  $form['submit_buttons'] = array(
    '#type' => 'fieldset',
    '#prefix' => '<span class="clear"></span><div class="container-inline"><div class="buttons">',
    '#suffix' => '</div></div>',
  );

  $form['submit_buttons'][$action] = array(
    '#type' => 'submit',
    '#value' => t($action ),
  );
  $form['submit_buttons']['Cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}


/**
 * Theme the new scripting form
 */
function theme_cloud_script_create($variables) {
  $form = $variables['form'];

  $rows = array(
    array(
      \Drupal::service("renderer")->render($form['name'          ]['name_label'           ] ),
      \Drupal::service("renderer")->render($form['name'          ]['name_text'            ] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['description'   ]['description_label'    ] ),
      \Drupal::service("renderer")->render($form['description'   ]['description_text'     ] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['script_type'   ]['script_type_label'    ] ),
      \Drupal::service("renderer")->render($form['script_type'   ]['script_type_select'   ] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['cloud_inputs'   ]['inputs_label'         ] ),
      \Drupal::service("renderer")->render($form['cloud_inputs'   ]['inputs_text'          ] ),
    ),
    array(
      \Drupal::service("renderer")->render($form['script_template']['script_template_label'] ),
      \Drupal::service("renderer")->render($form['script_template']['script_template_text'] ),
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

  $form['fieldset_script_info']['#children'] = $table;

  $output  = \Drupal::service("renderer")->render($form['launch']);
  $output .= \Drupal::service("renderer")->render($form['fieldset_script_info']);
  $output .= \Drupal::service("renderer")->render($form['submit_buttons']);
  $output .= drupal_render_children($form);

  return $output;
}


/**
 * Validate the new scripting form
 */
function cloud_script_create_validate($form_id, $form_values) {
  $form_values = $form_values['values'];
  if ( $form_values['op'] == t('Add' ) || $form_values['op'] == t('Edit') ) {
    if ( empty($form_values['name_text']) ) {
      form_set_error('name_text', t('You must enter valid Script Name'));
    }
    else {
      $query = _cloud_script_get_script_query();
      $query->condition('name', $form_values['name_text'], '=');
      $result = $query->execute()->fetchObject();

      if ($result) {
        $db_obj    = $result;
        $script_id = $db_obj->script_id;
        if ($script_id != $form_values['script_id']) {
          form_set_error('script_id', t('Name is already used by a Script.'));
        }
      }
    }

    if ( empty($form_values['script_template_text']) ) {
      form_set_error('script_template_text', t('You must enter valid script'));
    }
  }
}


/**
 * Submit function for adding a new script
  */
function cloud_script_create_submit($form_id, &$form_state) {
  $form_values = $form_state['values'];

  if ($form_values['op'] == t('Add')) {
    _cloud_script_insert_script($form_values);
    drupal_set_message(t('Script has been saved.'));
  }
  elseif ($form_values['op'] == t('Edit')) {
    $script_id = $form_values['script_id'];
    _cloud_script_update_script($form_values, $script_id );
    drupal_set_message(t('Script has been saved.'));
  }
  $form_state['redirect'] = CLOUD_SCRIPTING_PATH . '/list';
}

/**
 * Update script status for an instance
 */
function _cloud_script_update_instance_status($dns) {

  $instance_id = _get_instance_by_dns_from_db($dns);

  if ( _cloud_failover_is_endbled_check_by_instance_id($instance_id) > 0  ) {
    $result_script_status = _instance_status_update($instance_id, CLOUD_INSTANCE_STATUS_BOOTING );
  }
  else {
    $scripts_arr  = _cloud_script_get_scripts_for_instance($instance_id);
    if (sizeof($scripts_arr) == 0 ) {
      if ( _cloud_script_check_is_executed($instance_id) > 0 ) {
        // Scripts are to be executed
        $result_script_status = _instance_status_update($instance_id, CLOUD_INSTANCE_STATUS_BOOTING );
      }
      else {
        // No scripts present
        $result_script_status = _instance_status_update($instance_id, CLOUD_INSTANCE_STATUS_OPERATIONAL );
      }
    }
    else {
      // Scripts are to be executed
      $result_script_status = _instance_status_update($instance_id, CLOUD_INSTANCE_STATUS_BOOTING );
    }
  }
}

/**
 * Check if a script has been executed
 */
function _cloud_script_check_is_executed($instance_id) {

  $check_scripts_query = " SELECT COUNT(*) AS script_cnt FROM ( SELECT ds.template_id AS tid FROM cloud_cluster_servers ds
                    LEFT JOIN {" . CLOUD_SERVER_TEMPLATES_TABLE . "} st
                    ON ds.template_id=st.template_id
                    WHERE ds.instance_id=:instance_id 
                  ) tmp_tb 
                  LEFT JOIN {" . CLOUD_SERVER_TEMPLATES_SCRIPTS_TABLE . "} sts 
                  ON tmp_tb.tid=sts.server_template_id 
                  ";

  $check_scripts_query_args = array(
    ':instance_id' => $instance_id,
  );

  $result = db_query($check_scripts_query, $check_scripts_query_args );
  
  if ($result->fetchField() > 0 ) {
    return $script_cnt;
  }

  $check_scripts_array_query = "SELECT COUNT(*) AS script_cnt
                                FROM
                                (
                                  SELECT arrayid FROM 
                                  ( SELECT tas.arrayid as arrayid FROM {" . CLOUD_AUTO_SCALING_ARRAY_LAUNCH_TABLE . "} tas
                                    WHERE tas.instance_id=:instance_id ) vas
                                  LEFT JOIN {" . INSTANCE_ARRAY_TABLE . "} sia on vas.arrayid = sia.id 
                                  LEFT JOIN {" . CLOUD_SERVER_TEMPLATES_TABLE . "} st on sia.server_template_id=st.template_id
                                  LEFT JOIN {" . CLOUD_SERVER_TEMPLATES_SCRIPTS_TABLE . "} sts on st.template_id=sts.server_template_id 
                                ) cnt_tbl ";
  $check_scripts_array_query_args = array(
    ':instance_id' => $instance_id,
  );
  $result_arr = db_query($check_scripts_array_query, $check_scripts_array_query_args );
  return $result_arr->fetchField();
}

/**
 * Redirect to script listing page 
 */
function _cloud_script_getdata($src = '') {
  if ( $src == NULL || empty($src) ) {
    drupal_goto( CLOUD_SCRIPTING_PATH . '/list' );
  }
  else {
    drupal_goto( $src );
  }
}
