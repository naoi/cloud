<?php

namespace Drupal\cloud_server_template\Controller;

use Drupal\cloud_server_template\Entity\CloudServerTemplateInterface;

/**
 * Common interfaces for the CloudServerTemplateControllerInterface
 */
interface CloudServerTemplateControllerInterface {

  /**
   * Launch Operation
   * @return mixed
   */
  public function launch(CloudServerTemplateInterface $cloud_server_template);

}