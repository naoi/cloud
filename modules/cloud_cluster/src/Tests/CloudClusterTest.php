<?php

// Updated by yas 2016/06/23
// Created by yas 2016/05/25.

namespace Drupal\cloud_cluster\Tests;
use Drupal\Component\Utility\Random;

use Drupal\simpletest\WebTestBase;

// Created by yas 2016/05/25
// module_load_include('test', 'aws_cloud');.
define('CLOUD_CLUSTER_REPEAT_COUNT', 3);

/**
 * Tests CloudCluster.
 *
 * @group Cloud
 */
class CloudClusterTest extends WebTestBase {
  /* @FIXME extends AwsCloudTestCase { */

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['cloud',
    'cloud_cluster',
  ];

  /**
   * The profile to install as a basis for testing.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Set up test.
   */
  protected function setUp() {
    parent::setUp();

    $web_user = $this->drupalCreateUser([
      'add cloud cluster',
      'list cloud cluster',
      'view cloud cluster',
      'edit cloud cluster',
      'delete cloud cluster',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for cloud_cluster information.
   */
  public function testCloudCluster() {

    // Access to Cloud Cluster Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = 'default_cloud_context';

    // List Cloud Cluster for AWS.
    $this->drupalGet("/clouds/design/cloud_cluster");
    $this->assertResponse(200, t('HTTP 200: List | Cloud Cluster'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

    // Add a new cloud_cluster information.
    $add = $this->createCloudClusterTestData();
    for ($i = 0; $i < CLOUD_CLUSTER_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/cloud_cluster/add",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Add | A New Cloud Cluster Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The Cloud Cluster entity "@name" has been saved.', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The Cloud Cluster entity "@name" has been saved.', [
                        '@name' => $add[$i]['name'],
                      ])
                       );
      $this->assertText($add[$i]['name'],
                        t('Name: @name ', [
                          '@name' => $add[$i]['name'],
                        ]));

      // Make sure listing.
      $this->drupalGet("/clouds/design/cloud_cluster");
      $this->assertResponse(200, t('HTTP 200: List | Cloud Cluster #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'],
                        t("Make sure w/ Listing @num: @name", [
                          '@num'  => $j + 1,
                          '@name' => $add[$j]['name'],
                        ]));
      }
    }

    // Edit case.
    $edit = $this->createCloudClusterTestData();
    // 3 times.
    for ($i = 0; $i < CLOUD_CLUSTER_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/cloud_cluster/$num/edit",
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Edit | A New Cloud Cluster Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The Cloud Cluster entity "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The Cloud Cluster entity "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ])
                       );
      $this->assertText($edit[$i]['name'], t('Name: @name ', [
        '@name' => $edit[$i]['name'],
      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/design/cloud_cluster");
      $this->assertResponse(200, t('HTTP 200: List | Cloud Cluster #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$j]['name'],
                        t("Make sure w/ Listing @num: @name", [
                          '@num'  => $j + 1,
                          '@name' => $edit[$j]['name'],
                        ]));
      }
    }

    // Delete cloud_cluster Items.
    for ($i = 0; $i < CLOUD_CLUSTER_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/design/cloud_cluster/$num/delete");
      $this->drupalPostForm("/clouds/design/cloud_cluster/$num/delete",
                            [],
                            t('Delete'));
      $this->assertResponse(200, t('HTTP 200: Delete | Cloud Cluster #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));

      // Make sure listing.
      $this->drupalGet("/clouds/design/cloud_cluster");
      $this->assertResponse(200, t('HTTP 200: Delete | Cloud Cluster #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertNoText($edit[$j]['name'],
                          t("Make sure w/ Listing @num: @name", [
                            '@num'  => $j + 1,
                            '@name' => $edit[$j]['name'],
                          ]));
      }
    }

    // Filtering cloud_cluster information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/design/cloud_cluster", $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $cloud_cluster_id = CLOUD_CLUSTER_REPEAT_COUNT - 1 ;
    $this->assertText('x1', t('Confirm Item:') . ' ' . 'x1.large');
    $this->assertNoText(t('Notice' ), t('Make sure w/o Notice'  ));
    $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
     */
    // end
    //    } // End of foreach.
  }

  /**
   *
   */
  private function createCloudClusterTestData() {

    static $random;
    if (!$random) {
      $random = new Random();
    }

    // 3 times.
    for ($i = 0; $i < CLOUD_CLUSTER_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[] = [
      // 'cloud_context'  => $cloud_context  ,.
        'name'           => "Cloud Cluster #$num - " . date('Y/m/d - ') . $random->name(16, TRUE),
        'description'    => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y')
        . ' - SimpleTest Cloud Cluster Description - '
        . $random->string(32, TRUE),
      ];
    }

    return $data;
  }

}
