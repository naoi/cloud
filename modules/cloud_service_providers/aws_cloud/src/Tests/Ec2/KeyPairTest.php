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
define('AWS_CLOUD_KEY_PAIR_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Key Pair.
 *
 * @group AWS Cloud
 */
class KeyPairTest extends WebTestBase {
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
      'list aws cloud key pair',
      'add aws cloud key pair',
      'view aws cloud key pair',
      'edit aws cloud key pair',
      'delete aws cloud key pair',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for Key Pair information.
   */
  public function testKeyPair() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = $this->random->name(8);

    // List Key Pair for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/key_pair");
    $this->assertResponse(200, t('List | HTTP 200: Key Pair'));
    $this->assertNoText(t('Notice'), t('List | Make sure w/o Notice'));
    $this->assertNoText(t('warning'), t('List | Make sure w/o Warnings'));

    // Add a new Key Pair.
    $add = $this->createKeyPairTestData();
    for ($i = 0; $i < AWS_CLOUD_KEY_PAIR_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/key_pair/add",
                            $add[$i],
                            t('Save'));

      $this->assertResponse(200, t('Add | HTTP 200: A New AWS Cloud Key Pair #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Key Pair "@key_pair_name', [
        '@key_pair_name' => $add[$i]['key_pair_name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Add | The AWS Cloud Key Pair "@key_pair_name" has been created.', [
                        '@key_pair_name' => $add[$i]['key_pair_name'],
                      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/key_pair");
      $this->assertResponse(200, t('Add | List | HTTP 200: Key Pair #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | List | Make sure w/o Warnings'));

      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['key_pair_name'],
                        t('Make sure w/ Listing: @key_pair_name',
                                          ['@key_pair_name' => $add[$j]['key_pair_name']]));
      }
    }

    // Key Pair doesn't have edit operation
    //     // View an Key Pair information.
    //     $edit = $this->createKeyPairTestData();
    //     for ($i = 0; $i < AWS_CLOUD_KEY_PAIR_REPEAT_COUNT; $i++) {
    //
    //       $num = $i + 1;
    //
    //       $this->drupalGet("/clouds/aws_cloud/$cloud_context/key_pair/$num");
    //       $this->assertResponse(200, t('Edit | HTTP 200: A New AWS Cloud Key Pair #@num', array('@num' => $num)));
    //       $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
    //       $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
    //       $this->assertText($edit[$i]['key_pair_name'],
    //                         t('The AWS Cloud Key Pair: "@key_pair_name".', array('@name' => $edit[$i]['key_pair_name'],)));
    //
    //       // Make sure listing.
    //       $this->drupalGet("/clouds/aws_cloud/$cloud_context/key_pair");
    //       $this->assertResponse(200, t('Edti | List | HTTP 200: Key Pair #@num', array('@num' => $num)));
    //       $this->assertNoText(t('Notice'), t('Edti | List | Make sure w/o Notice'));
    //       $this->assertNoText(t('warning'), t('Edti | List | Make sure w/o Warnings'));
    //
    //       for ($j = 0; $j < $i + 1; $j++) {
    //         $this->assertText($edit[$i]['key_pair_name'],
    //                         t('Edit | List | Make sure w/ Listing: @key_pair_name', array(
    //                           '@name' => $edit[$i]['key_pair_name'],)));
    //       }
    //     }
    // Delete Key Pair.
    for ($i = 0; $i < AWS_CLOUD_KEY_PAIR_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/aws_cloud/$cloud_context/key_pair/$num/delete");
      $this->assertResponse(200, t('Delete | HTTP 200: Key Pair #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/key_pair/$num/delete",
                            [],
                            t('Delete'));

      $this->assertResponse(200, t('Delete | HTTP 200: The Cloud Key Pair #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->assertText($add[$i]['key_pair_name'], t('Name: @key_pair_name ', ['@key_pair_name' => $add[$i]['key_pair_name']]));
      $this->assertText(t('The AWS Cloud Key Pair "@key_pair_name" has been deleted.', [
        '@key_pair_name' => $add[$i]['key_pair_name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Delete | The AWS Cloud Key Pair "@key_pair_name" has been deleted.', [
                                                  '@key_pair_name' => $add[$i]['key_pair_name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/key_pair");
      $this->assertResponse(200, t('HTTP 200: Delete | Key Pair #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      /*
      for ($j = 0; $j < $i + 1; $j++) { // 3 times
      $this->assertNoText($add[$i]['key_pair_name'],
      t('Delete | List | Make sure w/ Listing: @key_pair_name', array(
      '@key_pair_name' => $add[$i]['key_pair_name'])));
      }
       */
    }

    // Filtering scripting information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/key_pair', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_KEY_PAIR_REPEAT_COUNT - 1 ;
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
  private function createKeyPairTestData() {

    $this->random = new Random();
    $data = [];

    for ($i = 0; $i < AWS_CLOUD_KEY_PAIR_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        'key_pair_name' => $this->random->name(15, TRUE),
      ];
    }
    return $data;
  }

}
