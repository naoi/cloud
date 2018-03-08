<?php

namespace Drupal\aws_cloud\Tests\Ec2;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/06/23
// Updated by yas 2016/06/05
// Updated by yas 2016/06/02
// Updated by yas 2016/05/31
// Updated by yas 2016/05/29
// Updated by yas 2016/05/25
// Created by yas 2016/05/23.
// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_VOLUME_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Volume.
 *
 * @group AWS Cloud
 */
class VolumeTest extends WebTestBase {
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
      'list aws cloud volume',
      'add aws cloud volume',
      'view aws cloud volume',
      'edit aws cloud volume',
      'delete aws cloud volume',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for Volume information.
   */
  public function testVolume() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = $this->random->name(8);

    // List Volume for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/volume");
    $this->assertResponse(200, t('HTTP 200: List'));
    $this->assertNoText(t('Notice'), t('List | Make sure w/o Notice'));
    $this->assertNoText(t('warning'), t('List | Make sure w/o Warnings'));

    // Add a new Volume.
    $add = $this->createVolumeTestData();
    for ($i = 0; $i < AWS_CLOUD_VOLUME_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/volume/add",
                            $add[$i],
                            t('Save'));

      $this->assertResponse(200, t('Add | HTTP 200: A New AWS Cloud Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | Make sure w/o Warnings'));
      $this->assertText($add[$i]['name'], t('Add | Volume: @name ', ['@name' => $add[$i]['name']]));
      $this->assertText(t('The AWS Cloud Volume "@name', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Add | The AWS Cloud Volume "@name" has been created.', [
                        '@name' => $add[$i]['name'],
                      ]));

      // Make sure View.
      /*
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/volume/$num");
      $this->assertResponse(200, t('Add | View | HTTP 200: Volume #@num', array('@num' => $num)));
      $this->assertNoText(t('Notice'), t('Add | View | ake sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | View | Make sure w/o Warnings'));
       */

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/volume");
      $this->assertResponse(200, t('HTTP 200: Add | List | Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | List | Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$i]['name'],
                        t('Add | List | Make sure w/ Listing: @name', [
                          '@name' => $add[$i]['name'],
                        ]));
      }
    }

    // Edit an Volume information.
    $edit = $this->createVolumeTestData();
    for ($i = 0; $i < AWS_CLOUD_VOLUME_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      unset($edit[$i]['snapshot_id']);
      unset($edit[$i]['size']);
      unset($edit[$i]['availability_zone']);
      unset($edit[$i]['iops']);
      unset($edit[$i]['encrypted']);

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/volume/$num/edit",
                            $edit[$i],
                            t('Save'));

      $this->assertResponse(200, t('HTTP 200: Edit | A New AWS Cloud Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Volume "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Edit | The AWS Cloud Volume "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/volume");
      $this->assertResponse(200, t('Edit | List | HTTP 200: Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));

      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$i]['name'],
                        t('Edit | List | Make sure w/ Listing: @name', [
                          '@name' => $edit[$i]['name'],
                        ]));
      }
    }

    // Delete Volume.
    for ($i = 0; $i < AWS_CLOUD_VOLUME_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/aws_cloud/$cloud_context/volume/$num/delete");
      $this->assertResponse(200, t('Delete | HTTP 200: Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/volume/$num/delete",
                            [],
                            t('Delete'));

      $this->assertResponse(200, t('Delete | HTTP 200: The Cloud Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Delete | Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Volume "@name" has been deleted.', [
        '@name' => $edit[$i]['name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Delete | The AWS Cloud Volume "@name" has been deleted.', [
                                                  '@name' => $edit[$i]['name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/volume");
      $this->assertResponse(200, t('Delete | HTTP 200: Volume #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      /*
      for ($j = 0; $j < $i + 1; $j++) { // 3 times
      $this->assertNoText($edit[$i]['name'],
      t('Delete | List | Make sure w/ Listing: @name', array(
      '@name' => $edit[$i]['name'])));
      }
       */
    }

    // Filtering scripting information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/volume', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_VOLUME_REPEAT_COUNT - 1 ;
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
  private function createVolumeTestData() {

    $this->random = new Random();
    $data = [];

    for ($i = 0; $i < AWS_CLOUD_VOLUME_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        'name'              => "volume-name #$num - " . $this->random->name(32, TRUE),
        'snapshot_id'       => "snapshot-id-$num-" . $this->random->name(8, TRUE),
        'size'              => $num * 10,
        'availability_zone' => "us-west-$num",
        'iops'              => $num * 1000,
        'encrypted'         => $num % 2,
      ];
    }
    return $data;
  }

}
