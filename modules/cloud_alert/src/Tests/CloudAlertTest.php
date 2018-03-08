<?php

namespace Drupal\cloud_alert\Tests;
use Drupal\Component\Utility\Random;

use Drupal\simpletest\WebTestBase;

// Updated by yas 2016/06/23
// Updated by yas 2016/05/25
// updated by yas 2016/05/24
// created by yas 2015/06/08.
// module_load_include('test', 'aws_cloud');.
define('CLOUD_ALERT_REPEAT_COUNT', 3);

/**
 * Tests CloudAlert.
 *
 * @group Cloud
 */
class CloudAlertTest extends WebTestBase {
  /* @FIXME extends AwsCloudTestCase { */

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['cloud',
    'cloud_alert',
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
      'add cloud alert',
      'list cloud alert',
      'view cloud alert',
      'edit cloud alert',
      'delete cloud alert',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for cloud_alert information.
   */
  public function testCloudAlert() {

    // Access to Cloud Alert Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = 'default_cloud_context';

    // List Cloud Alert for AWS.
    $this->drupalGet("/clouds/design/cloud_alert");
    $this->assertResponse(200, t('HTTP 200: List | Cloud Alert'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

    // Add a new cloud_alert information.
    $add = $this->createCloudAlertTestData();
    for ($i = 0; $i < CLOUD_ALERT_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/cloud_alert/add",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Add | A New Cloud Alert Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The Cloud Alert entity "@name" has been saved.', [
        '@name' => $add[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The Cloud Alert entity "@name" has been saved.', [
                        '@name' => $add[$i]['name'],
                      ])
                       );
      $this->assertText($add[$i]['name'],
                        t('Name: @name ', [
                          '@name' => $add[$i]['name'],
                        ]));

      // Make sure listing.
      $this->drupalGet("/clouds/design/cloud_alert");
      $this->assertResponse(200, t('HTTP 200: List | Cloud Alert #@num', ['@num' => $num]));
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
    $edit = $this->createCloudAlertTestData();
    // 3 times.
    for ($i = 0; $i < CLOUD_ALERT_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalPostForm("/clouds/design/cloud_alert/$num/edit",
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Edit | A New Cloud Alert Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      $this->assertText(t('The Cloud Alert entity "@name" has been saved.', [
        '@name' => $edit[$i]['name'],
      ]),
                        t('Confirm Message') . ': '
                      . t('The Cloud Alert entity "@name" has been saved.', [
                        '@name' => $edit[$i]['name'],
                      ])
                       );
      $this->assertText($edit[$i]['name'], t('Name: @name ', [
        '@name' => $edit[$i]['name'],
      ]));

      // Make sure listing.
      $this->drupalGet("/clouds/design/cloud_alert");
      $this->assertResponse(200, t('HTTP 200: List | Cloud Alert #@num', ['@num' => $num]));
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

    // Delete cloud_alert Items.
    for ($i = 0; $i < CLOUD_ALERT_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $this->drupalGet("/clouds/design/cloud_alert/$num/delete");
      $this->drupalPostForm("/clouds/design/cloud_alert/$num/delete",
                            [],
                            t('Delete'));
      $this->assertResponse(200, t('HTTP 200: Delete | Cloud Alert #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));

      // Make sure listing.
      $this->drupalGet("/clouds/design/cloud_alert");
      $this->assertResponse(200, t('HTTP 200: Delete | Cloud Alert #@num', ['@num' => $num]));
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

    // Filtering cloud_alert information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/clouds/design/cloud_alert", $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $cloud_alert_id = CLOUD_ALERT_REPEAT_COUNT - 1 ;
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
  private function createCloudAlertTestData() {

    static $random;
    if (!$random) {
      $random = new Random();
    }

    // 3 times.
    for ($i = 0; $i < CLOUD_ALERT_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[] = [
      // 'cloud_context'  => $cloud_context  ,.
        'name'           => "Cloud Alert #$num - " . date('Y/m/d - ') . $random->name(16, TRUE),
        'description'    => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y')
        . ' - SimpleTest Cloud Alert Description - '
        . $random->string(32, TRUE),
      ];
    }

    return $data;
  }

}
