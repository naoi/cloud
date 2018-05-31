<?php

/**
 * @file
 * Hooks related to aws_cloud module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alter the parameter array before being sent through the Aws Ec2 api
 * @param array $params
 * @param $operation
 * @param $cloud_context
 */
function hook_aws_cloud_pre_execute_alter(array &$params, $operation, $cloud_context) {

}

/**
 * Alter the results before it gets processed by aws_cloud
 * @param array $results
 * @param $operation
 * @param $cloud_context
 */
function hook_aws_cloud_post_execute_alter(array &$results, $operation, $cloud_context) {

}

/**
 * @} End of "addtogroup hooks".
 */