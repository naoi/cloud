<?php

namespace Drupal\aws_cloud\Tests\Ec2;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/06/23
// Updated by yas 2016/06/02
// Updated by yas 2016/05/31
// Updated by yas 2016/05/29
// Updated by yas 2016/05/25
// Updated by yas 2016/05/24
// Created by yas 2016/05/23.
// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_ELASTIC_IP_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Elastic IP.
 *
 * @group AWS Cloud
 */
class ElasticIpTest extends WebTestBase {
  /* @FIXME extends AwsCloudTestCase { */

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['cloud',
    'aws_cloud',
  ];

  /**
   * The profile to install as a basis for testing.
   *
   * @var string
   */
  protected $profile = 'minimal';

  protected $random;

  /**
   * Set up test.
   */
  protected function setUp() {
    parent::setUp();

    if (!$this->random) {
      $this->random = new Random();
    }

    $config = \Drupal::configFactory()->getEditable('aws_cloud.settings');
    $config->set('aws_cloud_test_mode', true);

    $web_user = $this->drupalCreateUser([
      'list aws cloud elastic ip',
      'add aws cloud elastic ip',
      'view aws cloud elastic ip',
      'edit aws cloud elastic ip',
      'delete aws cloud elastic ip',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for Elastic IP information.
   */
  public function testElasticIp() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = $this->random->name(8);

    // List Elastic IP for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip");
    $this->assertResponse(200, t('List | HTTP 200: Elastic IP'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));

    $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip/add");

    // Add a new Elastic IP.
    $add = $this->createElasticIpTestData();
    for ($i = 0; $i < AWS_CLOUD_ELASTIC_IP_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/elastic_ip/add",
                            $add[$i],
                            t('Save'));

      $this->assertResponse(200, t('Add | HTTP 200: A New AWS Cloud Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | Make sure w/o Warnings'));
      $this->assertText($add[$i]['name'], t('Elastic IP: @name ', ['@name' => $add[$i]['name']]));
      $this->assertText(t('The AWS Cloud Elastic IP "@name', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The AWS Cloud Elastic IP "@name" has been created.', [
                        '@name' => $add[$i]['name'],
                      ]));

      // Make sure View.
      /*
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip/$num");
      $this->assertResponse(200, t('Add | View | HTTP 200: Elastic IP #@num', array('@num' => $num)));
      $this->assertNoText(t('Notice'), t('Add | View | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | View | Make sure w/o Warnings'));
       */

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip");
      $this->assertResponse(200, t('Add | List | HTTP 200: Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | List | Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'],
                        t('Add | List | Make sure w/ Listing: @name', [
                          '@name' => $add[$i]['name'],
                        ]));
      }
    }

    // Edit an Elastic IP information.
    $edit = $this->createElasticIpTestData();
    for ($i = 0; $i < AWS_CLOUD_ELASTIC_IP_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      unset($edit[$i]['domain']);

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/elastic_ip/$num/edit",
                            $edit[$i],
                            t('Save'));

      $this->assertResponse(200, t('Edit | HTTP 200: A New AWS Cloud Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Elastic IP "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Edit | The AWS Cloud Elastic IP "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip");
      $this->assertResponse(200, t('Edit | List | HTTP 200: Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$j]['name'],
                        t('Make sure w/ Listing: @name', [
                          '@name' => $edit[$j]['name'],
                        ]));
      }
    }

    // Delete Elastic IP
    // 3 times.
    for ($i = 0; $i < AWS_CLOUD_ELASTIC_IP_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip/$num/delete");
      $this->assertResponse(200, t('Delete | HTTP 200: Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/elastic_ip/$num/delete",
                            [],
                            t('Delete'));

      $this->assertResponse(200, t('Delete | HTTP 200: The Cloud Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Elastic IP "@name" has been deleted.', [
        '@name' => $edit[$i]['name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Delete | The AWS Cloud Elastic IP "@name" has been deleted.', [
                                                  '@name' => $edit[$i]['name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/elastic_ip");
      $this->assertResponse(200, t('Delete | List | HTTP 200: Elastic IP #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | List | Make sure w/o Warnings'));
      /*
      for ($j = 0; $j < $i + 1; $j++) {
      $this->assertNoText($edit[$j]['name'],
      t('Delete | List | Make sure w/ Listing: @name', array(
      '@name' => $edit[$j]['name'])));
      }
       */
    }
    // Filtering scripting information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/elastic_ip', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_ELASTIC_IP_REPEAT_COUNT - 1 ;
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
  private function createElasticIpTestData() {

    $this->random = new Random();

    $data = [];
    // 3 times.
    for ($i = 0; $i < AWS_CLOUD_ELASTIC_IP_REPEAT_COUNT; $i++) {
      // Input Fields.
      $num = $i + 1;
      $data[$i] = [
        'name'        => "Elastic IP #$num - " . date('Y/m/d - ') . $this->random->name(15, TRUE),
        'domain'      => 'standard',
      ];
    }
    return $data;
  }

}
