<?php

namespace Drupal\aws_cloud\Tests\Ec2;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/06/23
// Updated by yas 2016/06/02
// Updated by yas 2016/05/31
// Updated by yas 2016/05/29
// Uppated by yas 2016/05/28
// Updated by yas 2016/05/25
// Created by yas 2016/05/23.
// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_SECURITY_GROUP_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Security Group.
 *
 * @group AWS Cloud
 */
class SecurityGroupTest extends WebTestBase {
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
      'list aws cloud security group',
      'add aws cloud security group',
      'view aws cloud security group',
      'edit aws cloud security group',
      'delete aws cloud security group',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for Security Group information.
   */
  public function testSecurityGroup() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = $this->random->name(8);

    // List Security Group for Amazon EC2.
    $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group");
    $this->assertResponse(200, t('List | HTTP 200: Security Group'));
    $this->assertNoText(t('Notice'), t('List | Make sure w/o Notice'));
    $this->assertNoText(t('warning'), t('List | Make sure w/o Warnings'));

    $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group/add");

    // Add a new Security Group.
    $add = $this->createSecurityGroupTestData();
    for ($i = 0; $i < AWS_CLOUD_SECURITY_GROUP_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/security_group/add",
                            $add[$i],
                            t('Save'));

      $this->assertResponse(200, t('Add | HTTP 200: A New AWS Cloud Security Group', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | Make sure w/o Warnings'));
      $this->assertText($add[$i]['group_name'], t('Add | Key Pair: @group_name ', ['@group_name' => $add[$i]['group_name']]));
      $this->assertText(t('The AWS Cloud Security Group "@group_name" has been created.', [
        '@group_name' => $add[$i]['group_name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('Add | The AWS Cloud Security Group "@group_name" has been created.', [
                        '@group_name' => $add[$i]['group_name'],
                      ]));

      // Make sure View.
      /*
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group/$num");
      $this->assertResponse(200, t('Add | View | HTTP 200: Security Group #@num', array('@num' => $num)));
      $this->assertNoText(t('Notice'), t('Add | View | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | View | Make sure w/o Warnings'));
       */

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group");
      $this->assertResponse(200, t('Add | List | HTTP 200: List | Security Group #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Add | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Add | List | Make sure w/o Warnings'));
      // 3 times.
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$i]['group_name'],
                        t('Add | List | Make sure w/ Listing: @group_name', [
                          '@group_name' => $add[$i]['group_name'],
                        ]));
      }
    }

    // Security Group doesn't have an edit operation.
    // Edit an Security Group information.
    //     $edit = $this->createSecurityGroupTestData();
    //     for ($i = 0; $i < AWS_CLOUD_SECURITY_GROUP_REPEAT_COUNT; $i++) {
    //
    //       $num = $i + 1;
    //
    //       unset($edit[$i]['description']);
    //
    //       $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/security_group/$num/edit",
    //                             $edit[$i],
    //                             t('Save'));
    //
    //       $this->assertResponse(200, t('Edit | HTTP 200: A New AWS Cloud Security Group #@num', array('@num' => $num)));
    //       $this->assertNoText(t('Notice'), t('Edit | Make sure w/o Notice'));
    //       $this->assertNoText(t('warning'), t('Edit | Make sure w/o Warnings'));
    //       $this->assertText(t('The AWS Cloud Security Group "@name" has been saved.', array(
    //         '@group_name' => $edit[$i]['group_name'],
    //       )),
    //                         t('Confirm Message') . ': '
    //                       . t('Edit | The AWS Cloud Security Group "@group_name" has been saved.', array(
    //                         '@group_name' => $edit[$i]['group_name'],
    //                       )));
    //
    //       $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group");
    //       $this->assertResponse(200, t('Edit | List | HTTP 200: Security Group #@num', array('@num' => $num)));
    //       $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
    //       $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));
    //
    //       $this->assertText($edit[$i]['group_name'], t('key_pair: @group_name ',
    //                               array('@group_name' => $edit[$i]['group_name'])));
    //
    //       // Make sure listing.
    //       $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group");
    //       $this->assertResponse(200, t('Edit | List | HTTP 200: Security Group #@num', array('@num' => $num)));
    //       $this->assertNoText(t('Notice'), t('Edit | List | Make sure w/o Notice'));
    //       $this->assertNoText(t('warning'), t('Edit | List | Make sure w/o Warnings'));
    //
    //       for ($j = 0; $j < $i + 1; $j++) {
    //         $this->assertText($edit[$i]['group_name'],
    //                         t('Edit | List | Make sure w/ Listing: @group_name', array(
    //                           '@group_name' => $edit[$i]['group_name'],
    //                         )));
    //       }
    //     }
    // Delete Security Group.
    for ($i = 0; $i < AWS_CLOUD_SECURITY_GROUP_REPEAT_COUNT; $i++) {
      $num = $i + 1;
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group/$num/delete");
      $this->assertResponse(200, t('Delete | HTTP 200: Security Group #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->drupalPostForm("/clouds/aws_cloud/$cloud_context/security_group/$num/delete",
                            [],
                            t('Delete'));

      $this->assertResponse(200, t('Delete | HTTP 200: The Cloud Security Group #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | Make sure w/o Warnings'));
      $this->assertText($add[$i]['group_name'], t('Group Name: @group_name ', ['@group_name' => $add[$i]['group_name']]));
      $this->assertText(t('The AWS Cloud Security Group "@group_name" has been deleted.', [
        '@group_name' => $add[$i]['group_name'],
      ]),
                                                  t('Confirm Message') . ': '
                                                . t('Delete | The AWS Cloud Security Group "@group_name" has been deleted.', [
                                                  '@group_name' => $add[$i]['group_name'],
                                                ]));

      // Make sure listing.
      $this->drupalGet("/clouds/aws_cloud/$cloud_context/security_group");
      $this->assertResponse(200, t('HTTP 200: Delete | Security Group #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Delete | List | Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Delete | List | Make sure w/o Warnings'));
      /*
      for ($j = 0; $j < $i + 1; $j++) { // 3 times
      $this->assertNoText($add[$i]['group_name'],
      t('Delete | List | Make sure w/ Listing: @group_name', array(
      '@group_name' => $add[$i]['group_name'])));
      }
       */
    }

    // Filtering scripting information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/aws_cloud/$cloud_context/security_group', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = AWS_CLOUD_SECURITY_GROUP_REPEAT_COUNT - 1 ;
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
  private function createSecurityGroupTestData() {

    $this->random = new Random();
    $data = [];

    for ($i = 0; $i < AWS_CLOUD_SECURITY_GROUP_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        'group_name'  => "group-name-#$num - " . $this->random->name(15, TRUE),
        'description' => "Description #$num - " . $this->random->name(64, TRUE),
      ];
    }
    return $data;
  }

}
