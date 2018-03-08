<?php

// Updated by yas 2016/06/23
// Updated by yas 2016/05/26
// Updated by yas 2016/05/25
// Updated by yas 2016/05/24
// Updated by yas 2016/05/23
// Ureated by yas 2015/06/08.

namespace Drupal\cloud_server_template\Tests;
use Drupal\Component\Utility\Random;

use Drupal\simpletest\WebTestBase;

// module_load_include('test', 'aws_cloud');.
define('CLOUD_SERVER_TEMPLATES_REPEAT_COUNT', 3);

/**
 * Tests CloudServerTemplate.
 *
 * @group Cloud
 */
class CloudServerTemplateTest extends WebTestBase {
  /* @FIXME extends AwsCloudTestCase { */

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['cloud',
    'cloud_server_template',
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
      'add cloud server template',
      'list cloud server template',
      'view cloud server template',
      'edit cloud server template',
      'delete cloud server template',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for server_template information.
   */
  public function testCloudServerTemplate() {

    // Access to Server Template Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = 'default_cloud_context';

    // List Server Template for AWS.
    $this->drupalGet("/clouds/design/server_template/$cloud_context");
    $this->assertResponse(200, t('HTTP 200: List | Server Template'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

    // Add a new server_template information.
    $add = $this->createServerTemplateTestData();
    for ($i = 0; $i < CLOUD_SERVER_TEMPLATES_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/server_template/$cloud_context/add",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Add | A New CloudServerTemplate Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The server template "@name" has been saved.', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The server template "@name" has been saved.', [
                        '@name' => $add[$i]['name'],
                      ])
                       );
      $this->assertText($add[$i]['name'],
                        t('Name: @name ', [
                          '@name' => $add[$i]['name'],
                        ]));

      // Make sure listing.
      $this->drupalGet("/clouds/design/server_template/$cloud_context");
      $this->assertResponse(200, t('HTTP 200: List | Server Template #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'],
                        t("Make sure w/ Listing @num: @name", [
                          '@num' => $j + 1,
                          '@name' => $add[$j]['name'],
                        ]));
      }
    }

    // Edit case.
    $edit = $this->createServerTemplateTestData();
    for ($i = 0; $i < CLOUD_SERVER_TEMPLATES_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/server_template/$cloud_context/$num/edit",
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Edit | A New Server Template #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The server template "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The server template "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ])
                       );
      $this->assertText($edit[$i]['name'], t('Name: @name ', [
        '@name' => $edit[$i]['name'],
      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/design/server_template/$cloud_context");
      $this->assertResponse(200, t('HTTP 200: List | Server Template #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($edit[$j]['name'],
                        t("Make sure w/ Listing @num: @name", [
                          '@num' => $j + 1,
                          '@name' => $edit[$j]['name'],
                        ]));
      }
    }

    // Delete server_template Items
    // 3 times.
    for ($i = 0; $i < CLOUD_SERVER_TEMPLATES_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/design/server_template/$cloud_context/$num/delete");
      $this->drupalPostForm("/clouds/design/server_template/$cloud_context/$num/delete",
                            [],
                            t('Delete'));
      $this->assertResponse(200, t('HTTP 200: Server Template | Delete #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));

      // Make sure listing.
      $this->drupalGet("/clouds/design/server_template/$cloud_context");
      $this->assertResponse(200, t('HTTP 200: List | Server Template #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertNoText($edit[$j]['name'],
                          t("Make sure w/ Listing @num: @name", [
                            '@num' => $j + 1,
                            '@name' => $edit[$j]['name'],
                          ]));
      }
    }
    // Filtering server_template information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/design/server_template", $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $server_template_id = CLOUD_SERVER_TEMPLATES_REPEAT_COUNT - 1 ;
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
  private function createServerTemplateTestData() {

    static $random;
    if (!$random) {
      $random = new Random();
    }

    // 3 times.
    for ($i = 0; $i < CLOUD_SERVER_TEMPLATES_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[] = [
      // 'cloud_context'  => $cloud_context  ,.
        'name'           => "Template #$num - " . date('Y/m/d') . $random->name(16, TRUE),
        'description'    => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y')
        . ' - SimpleTest Server Template Description - '
        . $random->string(32, TRUE),
        'instance_type'  => "m$num.xlarge",
        'image_id'       => 'ami-' . $random->name(8, TRUE),
        'kernel_id'      => 'aki-' . $random->name(8, TRUE),
        'ramdisk_id'     => 'ari-' . $random->name(8, TRUE),
        'ssh_key_id'     => 'ssh_key_id-' . $random->name(32, TRUE),
        'instance_count' => $num,
      ];
    }
    return $data;
  }

}
