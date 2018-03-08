<?php
use Drupal\simpletest\WebTestBase;

namespace Drupal\aws_cloud\Tests\Config;
/**
 *
 */
class ConfigTestCase extends WebTestBase {

  protected $profile = 'standard';

  protected $userList = [];

  protected $privileged_user;

  /**
   *
   */
  public function setUp() {

    parent::setUp('cloud', 'cloud_server_templates', 'cloud_scripting', 'cloud_pricing', 'cloud_alerts', 'cloud_activity_audit', 'cloud_cluster', 'aws_api', 'aws_lib', 'aws', 'aws_cloud');

    // Create and log in our privileged user with the basic permissions.
    $this->privileged_user = $this->drupalCreateUser([
      'access administration pages',
      'administer site configuration',
      'administer cloud',
      'access dashboard',
      'access audit report',
      'copy server template',
      'create server template',
      'delete server template',
      'edit server template',
      'launch server template',
      'list server templates',
      'set scripts and alerts',
      'view server template',
      'list alerts',
      'create alert',
      'view alerts',
      'edit alert',
      'delete alert',
      'create cluster',
      'delete cluster',
      'list clusters',
      'update cluster',
      'create script',
      'list scripts',
      'edit script',
      'delete script',
      'create pricing',
      'list pricing',
      'edit pricing',
      'delete pricing',
    ]);
    $this->userList[] = $this->privileged_user->name;
    $this->drupalLogin($this->privileged_user);
    $this->configure();
  }

  /**
   *
   */
  public function configure() {
    // Setup some sub-clouds in the system.
    foreach ($this->data as $key => $value) {
      $this->drupalPost('admin/settings/clouds/add', $value, t('Create'));
      $this->assertText(t('Cloud @cloud_name has been created', [
        '@cloud_name' => $value['cloud_name'],
      ]), 'Confirm Message: The cloud has been created');
    }

    // Reset the cached modules, permissions...etc.
    $this->resetAll();

    // Create a new user with permissions against the
    // newly enabled sub-clouds.
    $this->privileged_user = $this->drupalCreateUser([
      'access administration pages',
      'administer site configuration',
      'administer cloud',
      'access dashboard',
      'access audit report',
      'copy server template',
      'create server template',
      'delete server template',
      'edit server template',
      'launch server template',
      'list server templates',
      'set scripts and alerts',
      'view server template',
      'list alerts',
      'create alert',
      'view alerts',
      'edit alert',
      'delete alert',
      'create cluster',
      'delete cluster',
      'list clusters',
      'update cluster',
      'create script',
      'list scripts',
      'edit script',
      'delete script',
      'create pricing',
      'list pricing',
      'edit pricing',
      'delete pricing',
      // Amazon sub-cloud.
      'amazon_ec2 administer cloud',
      'amazon_ec2 list instances',
      'amazon_ec2 launch instance',
      'amazon_ec2 terminate all instances',
      'amazon_ec2 terminate own instance',
      'amazon_ec2 access all console',
      'amazon_ec2 access own console',
      'amazon_ec2 list images',
      'amazon_ec2 register image',
      'amazon_ec2 delete image',
      'amazon_ec2 list key fingerprints',
      'amazon_ec2 list key names',
      'amazon_ec2 register key',
      'amazon_ec2 update key',
      'amazon_ec2 delete key',
      'amazon_ec2 list IPs',
      'amazon_ec2 add IP',
      'amazon_ec2 delete IP',
      'amazon_ec2 assign IP',
      'amazon_ec2 update instance details',
      'amazon_ec2 list security group',
      'amazon_ec2 register security group',
      'amazon_ec2 setup security group',
      'amazon_ec2 delete security group',
      'amazon_ec2 list volume',
      'amazon_ec2 create volume',
      'amazon_ec2 delete volume',
      'amazon_ec2 attach volume',
      'amazon_ec2 detach volume',
      'amazon_ec2 list snapshot',
      'amazon_ec2 create snapshot',
      'amazon_ec2 delete snapshot',
      'amazon_ec2 display cpu load',
      'amazon_ec2 display traffic amount',
      'amazon_ec2 display storage space',
      'amazon_ec2 list template',
      'amazon_ec2 create template',
      'amazon_ec2 update template',
      'amazon_ec2 update own template',
      'amazon_ec2 delete template',
      'amazon_ec2 delete own template',
      'amazon_ec2 copy template',
      'amazon_ec2 access report',
      'access audit report',
      'copy server template',
      'create server template',
      'delete server template',
      'edit server template',
      'launch server template',
      'list server templates',
      'set scripts and alerts',
      'view server template',
    ]);
    $this->userList[] = $this->privileged_user->name;
    $this->drupalLogin($this->privileged_user);
  }

  /**
   * Tear Down function.
   */
  public function tearDown() {
    foreach ($this->userList as $name) {
      $this->tearDownSSHKey($name);
    }
    $this->tearDownSSHKEy($this->privileged_user->name);
    parent::tearDown();
  }

  /**
   * Data definition for test keys
   * For each new sub cloud, add its access variables.
   */
  private $data = [
    'amazon_ec2' => [
      'cloud_name' => 'amazon_ec2',
      'base_cloud' => 'amazon',
      'cloud_display_name' => 'Amazon EC2',
      'api_version' => AMAZON_EC2_API_VERSION,
      'host_uri' => AMAZON_EC2_HOST_URI,
      'aws_access_key' => AMAZON_EC2_AWS_ACCESS_KEY,
      'aws_secret_key' => AMAZON_EC2_API_SECRET_KEY,
      'user_id' => AMAZON_EC2_USER_ID,
      'image_upload_url' => AMAZON_S3_IMAGE_UPLOAD_URI,
      'image_register_url' => AMAZON_EC2_IMAGE_REGISTER_URI,
      'certificate' => AMAZON_EC2_X509_CERTIFICATE ,
    ],
  ];

  /**
   * Test ssh key deletion.
   */
  protected function tearDownSSHKey($key_name = '') {
    $clouds = $this->getClouds();
    foreach ($clouds as $cloud) {
      $this->deleteSSHKey($key_name, $cloud);
      $this->assertText(t('Key Pair deleted successfully: @keyname', [
        '@keyname' => $key_name,
      ]), t('Confirmed: Key Pair deleted successfully: @keyname', [
        '@keyname' => $key_name,
      ]));
      $this->assertNoText(t('Notice'), t('Make sure w/o Notice'));
      $this->assertNoText(t('warning'), t('Make sure w/o Warnings'));
    }
  }

  /**
   * Helper function to refresh a subcloud page.
   */
  protected function refreshInstances($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/get_instances_data', [
      'query' => [
        'destination' => 'clouds/' . $cloud . '/instances',
      ],
    ]);
  }

  /**
   * Helper function to refresh an images listing page.
   */
  protected function refreshImages($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/getimagedata', [
      'query' => [
        'destination' => 'clouds/' . $cloud . '/images',
      ],
    ]);
  }

  /**
   * Helper function to refresh a security group listing page.
   */
  protected function refreshSecurityGroups($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/get_security_group_data');
  }

  /**
   * Helper function to refresh ssh keys.
   */
  protected function refreshSSHKeys($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/get_ssh_keys_data');
  }

  /**
   * Helper function to refresh Elastic IP.
   */
  protected function refreshElasticIP($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/get_elastic_ips_data');
  }

  /**
   * Helper function to refresh Volumes.
   */
  protected function refreshVolumes($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/get_volumes_data');
  }

  /**
   * Helper function to refresh snapshots.
   */
  protected function refreshSnapshots($cloud) {
    $this->drupalGet('clouds/' . $cloud . '/get_snapshots_data');
  }

  /**
   * Helper function to get the cloud names in the database.
   * Using the cloud name, we can then run all the tests.
   * This function will change after multi region gets
   * implemented.
   */
  protected function getClouds() {
    $clouds = [];
    $query = db_query("select cloud_name from {cloud_clouds}");
    while ($result = db_result($query)) {
      $clouds[] = $result;
    }
    return $clouds;
  }

  /**
   * Function returns an IP address from the {cloud_aws_elastic_ip} table.
   */
  protected function getIp($cloud) {
    return db_result(db_query("select public_ip from {cloud_aws_elastic_ip} where cloud_type='%s' and public_ip_name = '%s'", [
      $cloud,
      '- changeme -',
    ]));
  }

  /**
   * Function finds a Volume by Nickname.
   */
  protected function getVolumeId($nickname, $cloud) {
    return db_result(db_query("select volume_id from {cloud_aws_ebs_volumes} where nickname = '%s' and cloud_type = '%s'", [
      'SimpleTest_Volume',
      $cloud,
    ]));
  }

  /**
   * Helper function to create ssh keys.
   */
  protected function createSSHKey($key_name, $cloud) {
    $edit = [
      'keyname_text' => $key_name,
      'cloud_context' => $cloud,
    ];
    $this->drupalPost('clouds/' . $cloud . '/ssh_keys/create', $edit, t('Create'));
  }

  /**
   * Helper function to delete ssh keys.
   */
  protected function deleteSSHKey($key_name, $cloud) {
    $this->drupalGet('clouds/' . $cloud . '/ssh_keys/delete', [
      'query' => [
        'key_name' => $key_name,
      ],
    ]);
  }

  /**
   * Helper function to refresh the clouds page.
   */
  protected function refreshPageAll() {
    $this->drupalGet('clouds/getdata', ['query' => ['src' => 'clouds']]);
  }

}
