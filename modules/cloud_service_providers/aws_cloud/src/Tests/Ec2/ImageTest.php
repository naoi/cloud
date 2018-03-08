<?php

namespace Drupal\aws_cloud\Tests\Ec2;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/06/23
// Updated by yas 2016/06/02
// Updated by yas 2016/05/31
// Updated by yas 2016/05/29
// Created by yas 2016/05/28.
// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_IMAGE_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Image.
 *
 * @group AWS Cloud
 */
class ImageTest extends WebTestBase {
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
      'add aws cloud image',
      'list aws cloud image',
      'view aws cloud image',
      'edit aws cloud image',
      'delete aws cloud image',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for image information.
   */
  public function testImage() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = 'default_cloud_context';

    // List Image for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/image");
    $this->assertResponse(200, t('List | HTTP 200: Image'));
    $this->assertNoText(t('Notice'), t('List | ake sure w/o Notice'));
    $this->assertNoText(t('warning'), t('List | Make sure w/o Warnings'));

    // Register a new Image.
    $add = $this->createImageTestData();
    // 3 times.
    for ($i = 0; $i < AWS_CLOUD_IMAGE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/image/add",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('Add | HTTP 200: A New Cloud Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | Make sure w/o Warnings'));
      $this->assertText($add[$i]['name'], t('Name: @name ', ['@name' => $add[$i]['name']]));
      $this->assertText(t('The AWS Cloud Image "@name', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Add | The AWS Cloud Image "@name" has been created.', [
                        '@name' => $add[$i]['name'],
                      ]));

      // Make sure View.
      /*
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/image/$num");
      $this->assertResponse(200, t('Add | View | HTTP 200: Image #@num', array('@num' => $num)));
      $this->assertNoText(t('Notice'), t('Add | View | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | View | Make sure w/o Warnings'));
       */

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/image");
      $this->assertResponse(200, t('Add | List | HTTP 200: Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | List | Make sure w/o Warnings'));

      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'],
                        t('Add | List | Make sure w/ Listing: @name', [
                          '@name' => $add[$j]['name'],
                        ]));
      }
    }

    // Edit an Image information.
    $edit = $this->createImageTestData();
    for ($i = 0; $i < AWS_CLOUD_IMAGE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      unset($edit[$i]['instance_id']);
      unset($edit[$i]['description']);

      // @FIXME - $num => $image_id
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/image/$num/edit",
                            $edit[$i],
                            t('Save'));

      $this->assertResponse(200, t('HTTP 200: Edit | A New Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Image "@name" has been saved.', ['@name' => $edit[$i]['name']]),
                        t('Confirm Message') . ': '
                      . t('The AWS Cloud Image "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/image");
      $this->assertResponse(200, t('Edit | List | HTTP 200: Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$i]['name'],
                        t('Edit | List | Make sure w/ Listing: @name', [
                          '@name' => $edit[$i]['name'],
                        ]));
      }
    }

    // Delete Image.
    for ($i = 0; $i < AWS_CLOUD_IMAGE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/aws_cloud/$cloud_context/image/$num/delete");
      $this->assertResponse(200, t('Delete | HTTP 200: Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/image/$num/delete",
                            [],
                            t('Delete'));

      $this->assertResponse(200, t('Delete | HTTP 200: The Cloud Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Image "@name" has been deleted.', [
        '@name' => $edit[$i]['name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Delete | The AWS Cloud Image "@name" has been deleted.', [
                                                  '@name' => $edit[$i]['name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/image");
      $this->assertResponse(200, t('Delete | List | HTTP 200: Image #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | List | Make sure w/o Warnings'));
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

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/image', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_IMAGE_REPEAT_COUNT - 1 ;
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
  private function createImageTestData() {

    $this->random = new Random();
    $data = [];

    for ($i = 0; $i < AWS_CLOUD_IMAGE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        'name'        => "Image #$num - " . date('Y/m/d - ') . $this->random->name(8, TRUE),
        'instance_id' => 'i-' . $this->random->name(8),
        'description' => 'description-' . $this->random->name(64),
      ];
    }
    return $data;
  }

}
