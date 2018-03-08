<?php

// Updated by yas 2016/09/07
// Updated by yas 2016/06/24
// Updated by yas 2016/06/23
// Updated by yas 2016/06/02
// Updated by yas 2016/05/31
// Updated by yas 2016/05/29
// Updated by yas 2016/05/25
// Updated by yas 2016/05/24
// Updated by yas 2016/05/23
// Updated by yas 2016/05/22
// Created by yas 2016/05/21.

namespace Drupal\aws_cloud\Tests\Ec2;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_INSTANCE_REPEAT_COUNT', 3);
define('AWS_CLOUD_CONFIG_REPEAT_COUNT', 1);

/**
 * Tests AWS Cloud Instance.
 *
 * @group AWS Cloud
 */
class InstanceTest extends WebTestBase {
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

      'list aws cloud provider',
      'add aws cloud provider',
      'view aws cloud provider',
      'edit aws cloud provider',
      'delete aws cloud provider',

      'add aws cloud instance',
      'list aws cloud instance',
      'view aws cloud instance',
      'edit aws cloud instance',
      'delete aws cloud instance',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for instance information.
   */
  public function testInstance() {

// @FIXME Do refactor for sparate function

    // Add a new Config information.
    $add = $this->createConfigTestData();
    for ($i = 0; $i < AWS_CLOUD_CONFIG_REPEAT_COUNT; $i++) {

      $num = $i + 1;
      $cloud_context[$i] = $add[$i]['cloud_context'];
      $label[$i]         = $add[$i]['label'];

      $this->drupalGet('/admin/config/services/cloud/aws_cloud/cloud_context/add');
      $this->assertResponse(200, t('HTTP 200: Add | AWS Cloud Config Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

      $this->drupalPostForm('/admin/config/services/cloud/aws_cloud/cloud_context/add',
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Saved | Aws Cloud Config Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      /*
      $this->assertText(t('AWS cloud information "%label" has been saved.', array('%label' => $label)),
      t('Add - Saved Message:')  . ' '
      . t('AWS cloud information "%label" has been saved.', array('%label' => $label)));
       */
      $this->assertText($label[$i],
                        t('Cloud Display Name: %label', [
                          '%label' => $label[$i],
                        ]));
      $this->assertText($cloud_context[$i],
                        t('cloud_context: @cloud_context', [
                          '@cloud_context' => $cloud_context[$i],
                        ]));

      // Make sure listing.
      $this->drupalGet('/admin/config/services/cloud/aws_cloud/cloud_context');
      $this->assertResponse(200, t('HTTP 200: List | AWS Cloud #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($label[$j],
                        t('Cloud Display Name @num: %label', [
                          '@num' => $j + 1,
                          '%label' => $label[$j],
                        ]));
        $this->assertText($cloud_context[$j],
                        t('Make sure w/ Listing @num: @cloud_context', [
                          '@num' => $j + 1,
                          '@cloud_context' => $cloud_context[$j],
                        ]));
      }
    }
// @FIXEME until here

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context =  $add[0]['cloud_context'];

    // List Instance for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/instance");
    $this->assertResponse(200, t('HTTP 200: List | Instance'));
    $this->assertNoText(t('Notice'), t('List | Make sure w/o Notice'));
    $this->assertNoText(t('warning'), t('List | Make sure w/o Warnings'));

    // Launch a new Instance.
    $add = $this->createInstanceTestData();
    for ($i = 0; $i < AWS_CLOUD_INSTANCE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/instance/launch",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Launch | A New Cloud Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Launch | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Launch | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Instance "@name', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Launch | The AWS Cloud Instance "@name" request has been initiated.', [
                        '@name' => $add[$i]['name'],
                      ]));
      $this->assertText($add[$i]['name'], t('Instance Name: @name ', ['@name' => $add[$i]['name']]));

      // Make sure View.
      /*
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/instance/$num");
      $this->assertResponse(200, t('Launch | View | HTTP 200: Instance #@num', array('@num' => $num)));
      $this->assertNoText(t('Notice'), t('Launch | View | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Launch | View | Make sure w/o Warnings'));
       */

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/instance");
      $this->assertResponse(200, t('Launch | List | HTTP 200: Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Launch | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Launch | List | Make sure w/o Warnings'));

      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'], t('Make sure w/ Listing: @name', [
          '@name' => $add[$j]['name'],
        ]));
        $this->assertText($add[$j]['key_pair_name'], t('Make sure w/ Listing: @key_pair_name', [
          '@key_pair_name' => $add[$j]['key_pair_name'],
        ]));
      }
    }

    // Edit an Instance information.
    $edit = $this->createInstanceTestData();
    for ($i = 0; $i < AWS_CLOUD_INSTANCE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      unset($edit[$i]['cloud_type']);
      unset($edit[$i]['image_id']);
      unset($edit[$i]['min_count']);
      unset($edit[$i]['max_count']);
      unset($edit[$i]['key_pair_name']);
      unset($edit[$i]['is_monitoring']);
      unset($edit[$i]['availability_zone']);
      unset($edit[$i]['security_groups']);
      unset($edit[$i]['instance_type']);
      unset($edit[$i]['kernel_id']);
      unset($edit[$i]['ramdisk_id']);
      unset($edit[$i]['user_data']);

      // @FIXME - $num => $instance_id
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/instance/$num/edit",
                            $edit[$i],
                            t('Save'));

      $this->assertResponse(200, t('Edit | HTTP 200:  A New Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Instance "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Edit | The AWS Cloud Instance "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ]));

      $this->assertResponse(200, t('Edit | HTTP 200: A New Cloud Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Instance "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Edit | The AWS Cloud Instance "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/instance");
      $this->assertResponse(200, t('Edit | List | HTTP 200: Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$i]['name'],
                        t('Edit | List | Make sure w/ Listing: @name', [
                          '@name' => $edit[$i]['name'],
                        ]));
      }
    }

    // Terminate Instance.
    for ($i = 0; $i < AWS_CLOUD_INSTANCE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/aws_cloud/$cloud_context/instance/$num/terminate");
      $this->assertResponse(200, t('Terminate: HTTP 200: Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Terminate | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Terminate | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/instance/$num/terminate",
                            [],
                            t('Delete | Terminate'));

      $this->assertResponse(200, t('Terminate | HTTP 200: The Cloud Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Terminate | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Terminate | Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Instance Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Instance "@name" has been terminated.', [
        '@name' => $edit[$i]['name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Terminate | The AWS Cloud Instance "@name" has been terminated.', [
                                                  '@name' => $edit[$i]['name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/instance");
      $this->assertResponse(200, t('Terminate | HTTP 200: Instance #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Terminate | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Terminate | List | Make sure w/o Warnings'));
      /*
      for ($j = 0; $j < $i + 1; $j++) { // 3 times
      $this->assertNoText($edit[$i]['name'],
      t('Terminate | List | Make sure w/ Listing: @name', array(
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

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/instance', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_INSTANCE_REPEAT_COUNT - 1 ;
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
  private function createInstanceTestData() {

    $this->random = new Random();
    $data = [];

    for ($i = 0; $i < AWS_CLOUD_INSTANCE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        'name'              => "Instance #$num - " . date('Y/m/d - ') . $this->random->name(8, TRUE),
//      'cloud_type'        => 'amazon_ec2', // already set
        'image_id'          => 'ami-' . $this->random->name(8, TRUE),
        'min_count'         => $num,
        'max_count'         => $num * 2,
        'key_pair_name'     => "key_pair-$num-" . $this->random->name(8, TRUE),
        'is_monitoring'     => 0,
        'availability_zone' => "us-west-$num",
        'security_groups'   => "security_group-$num-" . $this->random->name(8, TRUE),
        'instance_type'     => "t$num.small",
        'kernel_id'         => 'aki-' . $this->random->name(8, TRUE),
        'ramdisk_id'        => 'ari-' . $this->random->name(8, TRUE),
        'user_data'         => "User Data #$num: " . $this->random->string(64, TRUE),
        'login_username'    => "user-$num" . date('-Y/m/d-') . $this->random->name(8, TRUE),
      ];
    }
    return $data;
  }

  /**
   *
   */
  private function createConfigTestData() {

    $this->random = new Random();

    // Input Fields
    // 3 times.
    for ($i = 0; $i < AWS_CLOUD_CONFIG_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $data[] = [
        'cloud_context'      => "amazon_us_west_$num",
        'cloud_type'         => 'amazon_ec2',
        'label'              => "Amazon EC2 US West ($num) - " . $this->random->name(8, TRUE),
        'description'        => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y') . $this->random->string(64, TRUE),
        'api_version'        => date('Y-m-d'),
        'region'             => "us-west-$num",
        'endpoint'           => "ec2.us-west-$num.amazonaws.com",
        'aws_access_key'     => $this->random->name(20, TRUE),
        'aws_secret_key'     => $this->random->name(40, TRUE),
        'user_id'            => $this->random->name(16, TRUE),
        'image_upload_url'   => "https://s3.amazonaws.com/$num",
        'image_register_url' => "ec2.us-west-$num.amazonaws.com",
        'certificate'        => $this->random->string(255, TRUE),
      ];

    }

    return $data;
  }


}
