<?php

namespace Drupal\cloud\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Cloud config entities.
 */
class CloudConfigViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
