<?php

namespace Drupal\aws_cloud\Tests\Config;

use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\Random;

// Updated by yas 2016/06/02
// Updated by yas 2016/06/01
// Updated by yas 2016/05/29
// updated by yas 2016/05/26
// updated by yas 2016/05/25
// updated by yas 2016/05/24
// updated by yas 2016/05/23
// updated by yas 2016/05/20
// updated by yas 2016/05/19
// updated by yas 2015/06/14
// updated by yas 2015/06/09
// created by yas 2015/06/08.
// module_load_include('test', 'aws_cloud');.
define('AWS_CLOUD_CONFIG_REPEAT_COUNT', 3);

/**
 * Tests AWS Cloud Config.
 *
 * @group AWS Cloud
 */
class ConfigTest extends WebTestBase {
  /* @FIXME extends ConfigTestCase { */

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
    ]);
    $this->drupalLogin($web_user);
  }

  /**
   * Tests CRUD for pricing information.
   */
  public function testConfig() {

    // Access to AWS Cloud Menu
    //    $clouds = $this->getClouds();
    //    foreach ($clouds as $cloud) {.
    // List AWS Cloud for Amazon EC2.
    $this->drupalGet('/admin/config/services/cloud/aws_cloud/cloud_context');
    $this->assertResponse(200, t('HTTP 200: List | AWS Cloud'));
    $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
    $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));

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

    // Edit Config case.
    $edit = $this->createConfigTestData();
    for ($i = 0; $i < AWS_CLOUD_CONFIG_REPEAT_COUNT; $i++) {

      $num = $i + 1;

      $cloud_context[$i] = $edit[$i]['cloud_context'];
      $label[$i]         = $edit[$i]['label'];

      unset($edit[$i]['cloud_context']);
      unset($edit[$i]['cloud_type']);

      $this->drupalPostForm('/admin/config/services/cloud/aws_cloud/cloud_context/' . $cloud_context[$i] . '/edit',
                            $edit[$i],
                            t('Save'));
      $this->assertResponse(200, t('HTTP 200: Edit | AWS Cloud Form #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      /*
      $this->assertText(t('AWS cloud information "%label" has been saved.', array('%label' => $label)),
      t('Edit - Saved Message:')  . ' '
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
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
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

    // Delete Config Items.
    $delete = $this->createConfigTestData();
    for ($i = 0; $i < AWS_CLOUD_CONFIG_REPEAT_COUNT; $i++) {

      $num = $i + 1;
      $cloud_context[$i] = $delete[$i]['cloud_context'];
      $label[$i] = $delete[$i]['label'];

      $this->drupalGet('/admin/config/services/cloud/aws_cloud/cloud_context/' . $cloud_context[$i] . '/delete');
      $this->drupalPostForm('/admin/config/services/cloud/aws_cloud/cloud_context/' . $cloud_context[$i] . '/delete',
                            [],
                            t('Delete'));
      $this->assertResponse(200, t('HTTP 200: Delete | AWS Cloud'));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      /*
      $this->assertText(t('content aws_cloud: deleted %label.', array('%label' => $label)),
      t('Deleted Message:')  . ' '
      . t('content aws_cloud: deleted %label.', array('%label' => $label)));
       */
      $this->assertNoText($label[$i],
                          t('Cloud Display Name: %label', [
                            '%label' => $label[$i],
                          ]));
      $this->assertNoText($cloud_context[$i],
                          t('cloud_context: @cloud_context', [
                            '@cloud_context' => $cloud_context[$i],
                          ]));
      // Because $cloud_context has been deleted.
      // Make sure listing.
      $this->drupalGet('/admin/config/services/cloud/aws_cloud/cloud_context');
      $this->assertResponse(200, t('HTTP 200: List | AWS Cloud #@num', ['@num' => $num]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('Warning'), t('Make sure w/o Warnings'));
      for ($j = 0; $j < $i + 1; $j++) {
        $this->assertNoText($label[$j],
                          t('Cloud Display Name @num: %label', [
                            '@num' => $j + 1,
                            '%label' => $label[$j],
                          ]));
        $this->assertNoText($cloud_context[$j],
                          t('Make sure w/ Listing @num: @cloud_context', [
                            '@num' => $j + 1,
                            '@cloud_context' => $cloud_context[$j],
                          ]));
      }
    }
    // Filtering pricing information item
    /*
    $filter = array(
    'filter'    => 't1',
    'operation' => 0,
    );

    $this->drupalPost("/admin/config/services/cloud/aws_cloud/cloud_context/$cloud_context", $filter, t('Apply'));
    $this->assertResponse(200, t('HTTP 200: Search Listings | Filter'));
    $xxxxx = AWS_CLOUD_CONFIG_REPEAT_COUNT - 1 ;
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
