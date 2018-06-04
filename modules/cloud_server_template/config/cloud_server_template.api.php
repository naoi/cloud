<?php

/**
 * @file
 * Hooks related to cloud_server_template module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alter the route array after a template is launched.
 *
 * @param array $route
 *  Associate array with route_name, params
 * @param \Drupal\cloud_server_template\Entity\CloudServerTemplateInterface $cloud_server_template
 */
function hook_cloud_server_template_post_launch_redirect_alter(array &$route, Drupal\cloud_server_template\Entity\CloudServerTemplateInterface $cloud_server_template) {

}

/**
 * @} End of "addtogroup hooks".
 */