<?php

namespace Drupal\aws_cloud\Tests\Ec2;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/06/23
// Updated by yas 2016/06/05
// Updated by yas 2016/06/02
// Updated by yas 2016/05/31
// Updated by yas 2016/05/29
// Updated by yas 2016/05/28
// Updated by yas 2016/05/25
// Updated by yas 2016/05/24
// Created by yas 2016/05/23.
// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_NETWORK_INTERFACE_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Network Interface.
 *
 * @group AWS Cloud
 */
class NetworkInterfaceTest extends WebTestBase {
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
      'list aws cloud network interface',
      'add aws cloud network interface',
      'view aws cloud network interface',
      'edit aws cloud network interface',
      'delete aws cloud network interface',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for Network Interface information.
   */
  public function testNetworkInterface() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = $this->random->name(8);

    // List Network Interface for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface");
    $this->assertResponse(200, t('List | HTTP 200: Network Interface'));
    $this->assertNoText(t('Notice'), t('List | Make sure w/o Notice'));
    $this->assertNoText(t('warning'), t('List | Make sure w/o Warnings'));

    // Add a new Network Interface.
    $add = $this->createNetworkInterfaceTestData();
    for ($i = 0; $i < AWS_CLOUD_NETWORK_INTERFACE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/network_interface/add",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('Add | HTTP 200: A New AWS Cloud Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Network Interface "@name', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Add | The AWS Cloud Network Interface "@name" has been created.', [
                        '@name' => $add[$i]['name'],
                      ]));
      $this->assertText($add[$i]['name'],
                       t('Network Interface: @name ', [
                         '@name' => $add[$i]['name'],
                       ]));

      // Make sure View.
      /*
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface/$num");
      $this->assertResponse(200, t('Add | View | HTTP 200: Network Interface #@num', array('@num' => $num)));
      $this->assertNoText(t('Notice'), t('Add | View | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | View | Make sure w/o Warnings'));
       */

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface");
      $this->assertResponse(200, t('Add | List | HTTP 200: Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | List | Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'],
                        t('Make sure w/ Listing: @name',
                                          ['@name' => $add[$j]['name']]));
      }
    }

    // Edit an Network Interface information.
    $edit = $this->createNetworkInterfaceTestData();
    for ($i = 0; $i < AWS_CLOUD_NETWORK_INTERFACE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      unset($edit[$i]['subnet_id']);
      unset($edit[$i]['description']);
      unset($edit[$i]['primary_private_ip']);
      unset($edit[$i]['secondary_private_ips']);
      unset($edit[$i]['is_primary']);
      unset($edit[$i]['security_groups']);

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/network_interface/$num/edit",
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('Edit | HTTP 200: A New AWS Cloud Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
      $this->assertText(t('The AWS Cloud Network Interface "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The AWS Cloud Network Interface "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ]));

      $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface");
      $this->assertResponse(200, t('Edit List | HTTP 200: Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));

      $this->assertText($edit[$i]['name'], t('Network Interface: @name ', ['@name' => $edit[$i]['name']]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface");
      $this->assertResponse(200, t('Edit | List | HTTP 200: Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));

      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$i]['name'],
                        t('Edit | List | Make sure w/ Listing: @name', [
                          '@name' => $edit[$i]['name'],
                        ]));
      }
    }

    // Delete Network Interface.
    for ($i = 0; $i < AWS_CLOUD_NETWORK_INTERFACE_REPEAT_COUNT; $i++) {
      $num = $i + 1;
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface/$num/delete");
      $this->assertResponse(200, t('HTTP 200: Delete | Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/network_interface/$num/delete",
                            [],
                            t('Delete'));

      $this->assertResponse(200, t('Delete | HTTP 200: The Cloud Network Interface #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->assertText($edit[$i]['name'], t('Name: @name ', ['@name' => $edit[$i]['name']]));
      $this->assertText(t('The AWS Cloud Network Interface "@name" has been deleted.', [
        '@name' => $edit[$i]['name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Delete | The AWS Cloud Network Interface "@name" has been deleted.', [
                                                  '@name' => $edit[$i]['name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/network_interface");
      $this->assertResponse(200, t('Delete | HTTP 200: Network Interface #@num', ['@num' => $num]));
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

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/network_interface', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_NETWORK_INTERFACE_REPEAT_COUNT - 1 ;
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
  private function createNetworkInterfaceTestData() {

    $this->random = new Random();
    $data = [];

    for ($i = 0; $i < AWS_CLOUD_NETWORK_INTERFACE_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        'name'                  => $this->random->name(32, TRUE),
        'description'           => "Description #$num - " . $this->random->name(64, TRUE),
        'subnet_id'             => 'subnet_id-' . $this->random->name(15, TRUE),
        'primary_private_ip'    => implode('.', [rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255)]),
        'secondary_private_ips' => implode('.', [rand(1, 254), rand(0, 254), rand(0, 254), rand(1, 255)]),
        'is_primary'            => $num % 2,
        'security_groups'       => 'sg-' . $this->random->name(32, TRUE),
      ];
    }
    return $data;
  }

}
