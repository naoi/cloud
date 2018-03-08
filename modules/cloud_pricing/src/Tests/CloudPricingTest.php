<?php

namespace Drupal\cloud_pricing\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/05/25
// updated by yas 2016/05/24
// updated by yas 2016/05/23
// updated by yas 2015/06/08
// created by yas 2015/06/05.
// module_load_include('test', 'aws_cloud');.
define('CLOUD_PRICING_REPEAT_COUNT', 3);

/**
 * Tests CloudPricing.
 *
 * @group Cloud
 */
class CloudPricingTest extends WebTestBase {
  /* @FIXME extends AwsCloudTestCase { */

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['cloud',
    'cloud_pricing',
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
      'add cloud pricing',
      'list cloud pricing',
      'view cloud pricing',
      'edit cloud pricing',
      'delete cloud pricing',
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for pricing information.
   */
  public function testCloudPricing() {

    // Access to Pricing Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    $cloud_context = 'default_cloud_context';

    // List Pricing for Amazon EC2.
    $this->drupalGet("/admin/config/cloud/$cloud_context/pricing");
    $this->assertResponse(200, t('HTTP 200: List | Pricing'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

    $this->drupalGet("/admin/config/cloud/$cloud_context/pricing/add");

    // Add a new pricing information.
    $add = $this->createPricingTestData();
    for ($i = 0; $i < CLOUD_PRICING_REPEAT_COUNT; $i++) {

      $num = $i + 1;
      $instance_type[$i] = $add[$i]['instance_type'];

      $this->drupalPostForm("/admin/config/cloud/$cloud_context/pricing/add",
                            $add[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Add | A New CloudPricing Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      $this->assertText($instance_type[$i] . ' ' . t('pricing information has been saved.'),
                        t('Confirm Message:') . ' '
                      . $instance_type[$i] . ' ' . t('pricing information has been saved.'));

      $this->assertText($instance_type[$i],
                               t('Instance Type: @instance_type', [
                                 '@instance_type' => $instance_type[$i],
                               ]));
      $this->assertText(number_format($add[$i]['linux_usage'], 3),
                               t('Linux Usage: @linux_usage', [
                                 '@linux_usage'      => $add[$i]['linux_usage'],
                               ]));
      $this->assertText(number_format($add[$i]['windows_usage'], 3),
                             t('Windows Usage: @windows_usage', [
                               '@windows_usage' => $add[$i]['windows_usage'],
                             ]));

      // Make sure listing.
      $this->drupalGet("/admin/config/cloud/$cloud_context/pricing");
      $this->assertResponse(200, t('HTTP 200: List | Pricing #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($instance_type[$j],
                        t("Make sure w/ Listing @num: @instance_type", [
                          '@num'  => $j + 1,
                          '@instance_type' => $instance_type[$j],
                        ]));
      }
    }

    // Edit case.
    $edit = $this->createPricingTestData();
    $instance_type = [];
    // 3 times.
    for ($i = 0; $i < CLOUD_PRICING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $id = $cloud_context . '.' . $edit[$i]['instance_type'];
      $instance_type[$i] = $edit[$i]['instance_type'];

      // Do not send $edit[$i]['instance_type'] as a POST parameter.
      unset($edit[$i]['instance_type']);
      $this->drupalPostForm("/admin/config/cloud/$cloud_context/pricing/$id",
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Edit | A New Pricing Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      $this->assertText($instance_type[$i] . ' ' . t('pricing information has been saved.'),
                        t('Confirm Message:') . ' '
                      . $instance_type[$i] . ' ' . t('pricing information has been saved.'));

      $this->assertText($instance_type[$i],
                        t('Instance Type: @instance_type', [
                          '@instance_type' => $instance_type[$i],
                        ]));
      $this->assertText(number_format($edit[$i]['linux_usage'], 3),
                                t('Linux Usage: @linux_usage', [
                                  '@linux_usage'   => $edit[$i]['linux_usage'],
                                ]));
      $this->assertText(number_format($edit[$i]['windows_usage'], 3),
                              t('Windows Usage: @windows_usage', [
                                '@windows_usage' => $edit[$i]['windows_usage'],
                              ]));

      // Make sure listing.
      $this->drupalGet("/admin/config/cloud/$cloud_context/pricing");
      $this->assertResponse(200, t('HTTP 200: List | Pricing #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertText($instance_type[$j],
                        t("Make sure w/ Listing @num: @instance_type", [
                          '@num' => $j + 1,
                          '@instance_type' => $instance_type[$j],
                        ]));
      }
    }

    // Delete pricing Items
    // 3 times.
    for ($i = 0; $i < CLOUD_PRICING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $id = $cloud_context . '.' . $instance_type[$i];

      $this->drupalGet("/admin/config/cloud/$cloud_context/pricing/$id/delete");
      $this->drupalPostForm("/admin/config/cloud/$cloud_context/pricing/$id/delete",
                            [],
                            t('Delete'));
      $this->assertResponse(200, t('HTTP 200: Pricing | Delete #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));

      // Make sure listing.
      $this->drupalGet("/admin/config/cloud/$cloud_context/pricing");
      $this->assertResponse(200, t('HTTP 200: List | Pricing #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertNoText($instance_type[$j],
                          t("Make sure w/ Listing @num: @instance_type", [
                            '@num'  => $j + 1,
                            '@instance_type' => $instance_type[$j],
                          ]));
      }
    }
    // Filtering pricing information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/admin/config/cloud/$cloud_context/pricing", $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $pricing_id = CLOUD_PRICING_REPEAT_COUNT - 1 ;
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
  private function createPricingTestData() {

    static $random;
    if (!$random) {
      $random = new Random();
    }

    for ($i = 0; $i < CLOUD_PRICING_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      // Input Fields.
      $data[] = [
        'instance_type' => "m$num.xlarge",
      // 12.34 (min . sec)
        'linux_usage'   => date('i.s'),
      // 12.34 (min . sec)
        'windows_usage' => date('i.s'),
        'description'   => "#$num: " . date('Y/m/d H:i:s - D M j G:i:s T Y')
        . ' - SimpleTest Pricing Description'
        . $random->string(32, TRUE),
      ];
    }

    return $data;
  }

}
