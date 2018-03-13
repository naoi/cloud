<?php

namespace Drupal\aws_cloud\Plugin;

use Drupal\cloud_server_template\Plugin\CloudServerTemplatePluginInterface;
use Drupal\Component\Plugin\PluginBase;

class AwsCloudServerTemplatePlugin extends PluginBase implements CloudServerTemplatePluginInterface {

  /**
   * The entity bundle tied to this implementation of CloudServerTemplate
   * @var string
   */
  private $entityBundleName = 'aws_cloud';

  /**
   * Returns the entity bundle
   * @return string
   */
  public function getEntityBundleName() {
    return $this->entityBundleName;
  }

}
