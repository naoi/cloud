<?php

// Updated by yas 2016/06/23
// Updated by yas 2016/05/25
// Updated by yas 2016/05/24
// Updated by yas 2016/05/23
// Updated by yas 2015/06/08
// Created by yas 2015/06/05.

namespace Drupal\cloud_script\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// module_load_include('test', 'aws_cloud');.

define('CLOUD_SCRIPTING_REPEAT_COUNT', 3);

/**
 * Tests CloudScript.
 *
 * @group Cloud
 */
class CloudScriptTest extends WebTestBase {
  /* @FIXME extends AwsCloudTestCase { */

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['cloud',
    'cloud_script',
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
      'add cloud script',
      'list cloud script',
      'view cloud script',
      'edit cloud script',
      'delete cloud script',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for scripting information.
   */
  public function testCloudScript() {

    // Access to Scripting Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = 'default_cloud_context';

    // List Scripting for Amazon EC2.
    $this->drupalGet('/clouds/design/script');
    $this->assertResponse(200, t('HTTP 200: List | Scripting'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

    $this->drupalGet('/clouds/design/script/add');

    // Add a new scripting information.
    $add = $this->createScriptingTestData();
    // 3 times.
    for ($i = 0; $i < CLOUD_SCRIPTING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm('/clouds/design/script/add',
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Add | A New CloudScript Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The scripts "@name" has been saved.', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The scripts "@name" has been saved.', [
                        '@name' => $add[$i]['name'],
                      ])
                       );
      $this->assertText($add[$i]['name'],
                        t('Name: @name ', [
                          '@name' => $add[$i]['name'],
                        ]));

      // Make sure listing.
      $this->drupalGet('/clouds/design/script');
      $this->assertResponse(200, t('HTTP 200: List | Scripting #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($add[$j]['name'],
                        t("Make sure w/ Listing @num: @name", [
                          '@num'  => $j + 1,
                          '@name' => $add[$j]['name'],
                        ]));
      }
    }

    // Edit case.
    $edit = $this->createScriptingTestData();
    // 3 times.
    for ($i = 0; $i < CLOUD_SCRIPTING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/script/$num/edit",
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Edit | A New Scripting Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The scripts "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The scripts "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ])
                       );
      $this->assertText($edit[$i]['name'],
                        t('Name: @name ', [
                          '@name' => $edit[$i]['name'],
                        ]));

      // Make sure listing.
      $this->drupalGet('/clouds/design/script');
      $this->assertResponse(200, t('HTTP 200: List | Scripting #@num', ['@num' => $num]));
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

    // Delete scripting Items
    // 3 times.
    for ($i = 0; $i < CLOUD_SCRIPTING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/design/script/$num/delete");
      $this->drupalPostForm("/clouds/design/script/$num/delete",
                            [],
                            t('Delete'));
      $this->assertResponse(200, t('HTTP 200: Delete | Scripting #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));

      // Make sure listing.
      $this->drupalGet('/clouds/design/script');
      $this->assertResponse(200, t('HTTP 200: List | Scripting #@num', ['@num' => $num]));
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

    // Filtering scripting information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost('/clouds/design/script', $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $scripting_id = CLOUD_SCRIPTING_REPEAT_COUNT - 1 ;
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
  private function createScriptingTestData() {

    static $random;
    if (!$random) {
      $random = new Random();
    }

    $data = [];
    // 3 times.
    for ($i = 0; $i < CLOUD_SCRIPTING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[$i] = [
        // 'cloud_context'  => $cloud_context,.
        'name'             => "Script #$num - " . date('Y/m/d') . $random->name(8, TRUE),
        'description'      => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y') . $random->name(8, TRUE)
        . ' - SimpleTest Scripting Description',
        'type'             => 'Boot-' . $random->name(8, TRUE),
        'input_parameters' => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y')
        . ' - SimpleTest Scripting Input Parameters',
        'script'           => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y')
        . ' - SimpleTest Scripting Script' . $random->string(32, TRUE),
      ];
    }
    return $data;
  }

}
