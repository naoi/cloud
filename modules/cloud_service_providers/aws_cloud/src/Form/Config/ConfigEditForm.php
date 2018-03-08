<?php

// Updated by yas 2016/10/31
// Updated by yas 2016/10/21
// Updated by yas 2016/07/05
// Updated by yas 2016/07/03
// Updated by yas 2016/06/24
// Updated by yas 2016/06/23
// Updated by yas 2016/06/20
// Updated by yas 2016/06/13
// Updated by yas 2016/06/01
// Updated by yas 2016/05/25
// updated by yas 2016/05/23
// updated by yas 2016/05/20
// updated by yas 2016/05/19
// updated by yas 2015/04/18
// updated by yas 2015/06/14
// updated by yas 2015/06/08
// created by yas 2015/06/05.
namespace Drupal\aws_cloud\Form\Config;

use Drupal\cloud\Form\CloudConfigForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigEditForm.
 *
 * @package Drupal\aws_cloud\Entity\Config\Form
 */
class ConfigEditForm extends CloudConfigForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);
    $entity = $this->entity;

    // Get a parameter from the path.
    //  $cloud_context = \Drupal::routeMatch()->getParameter('cloud_context');
    $form['cloud_context'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('ID (machine_name)'),
      '#default_value' => $entity->isNew()
    // It's new.
      ? ''
    // Get default value.
      : $entity->cloud_context(),
      '#description'   => $entity->isNew()
      ? $this->t('Cloud ID as an identifier (machine_name). This value cannot be edited once saved: e.g. aws_us_east | aws_us_west_1 | aws_us_west_2 | aws_eu_west_1 | aws_eu_central_1 | aws_ap_south_1 | aws_ap_northeast_1 | aws_ap_northeast_2 | aws_ap_southeast_1 | aws_ap_southeast_2 | aws_sa_east_1 | openstack_nova - The Cloud Name must contain only lowercase letters, numbers, and underscores.  Space cannot be included.')
      : $this->t('This value cannot be edited once saved.'),
      '#machine_name' => [
        'exists' => '\Drupal\aws_cloud\Entity\Config\Entity\Config::load',
      ],
      '#maxlength'     => 255,
      '#disabled'      => !$entity->isNew(),
    ];

    $cloud_types = array_keys($this->_aws_cloud_base_cloud_options());
    $form['cloud_type'] = [
      '#type'          => 'select',
      '#title'         => t('Cloud Type'),
      '#description'   => $entity->isNew()
      ? $this->t('Select the cloud type for pre-defined billing model for hourly rate.')
      : $this->t('This value cannot be edited once saved.'),
      '#options'       => $this->_aws_cloud_base_cloud_options(),
      '#default_value' => $entity->cloud_type() !== NULL ? $entity->cloud_type() : $cloud_types[0], // default: 'amazon_ec2'
      '#required'      => TRUE,
      '#disabled'      => !$entity->isNew(),
    ];

    $form['label'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Display Name'),
      '#description'   => $this->t('Label for display name.  e.g. US East (N. Virginia) (or AWS Virgina) | US East (Ohio) (or AWS Ohio) | US West (N. California) (or AWS California) | US West (Oregon) (or AWS Oregon) | Canada (Central) (or AWS Canada) | EU (Ireland) (or AWS Ireland) | EU (Frankfurt)  (or AWS Frankfurt) | EU (London) (or AWS London) | Asia Pacific (Mumbai) (or AWS Mumbai) | Asia Pacific (Tokyo) (or AWS Tokyo) | Asia Pacific (Seoul) (or AWS Seoul) | Asia Pacific (Singapore) (or AWS Singapore) | Asia Pacific (Sydney) (or AWS Sydney) | South America (São Paulo) (or AWS São Paulo) | OpenStack | Eucalyptus'),
      '#default_value' => $entity->label(),
      '#maxlength'     => 255,
      '#required'      => TRUE,
    ];

    $form['description'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Description'),
      '#default_value' => $entity->description(),
      '#cols'          => 60,
      '#rows'          => 3,
      '#required'      => FALSE,
    ];

    $form['api_version'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('API Version'),
      '#description'   => $this->t("The API Version of REST API (yyyy-mm-dd) or a string 'latest'"),
      '#default_value' => $entity->api_version() !== NULL ? $entity->api_version() : 'latest',
      '#maxlength'     => 255,
      '#required'      => TRUE,
    ];

    $form['endpoint'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('API Endpoint URI'),
      '#description'   => $this->t("'https://' is REQUIRED: e.g. https://ec2.amazonaws.com | https://ec2.us-east-2.amazonaws.com | https://ec2.us-west-1.amazonaws.com | https://ec2.us-west-2.amazonaws.com | https://ec2.ca-central-1.amazonaws.com | https://ec2.eu-west-1.amazonaws.com | https://ec2.eu-central-1.amazonaws.com | https://ec2.eu-west-2.amazonaws.com | https://ec2.ap-south-1.amazonaws.com | https://ec2.ap-northeast-1.amazonaws.com | https://ec2.ap-northeast-2.amazonaws.com | https://ec2.ap-southeast-1.amazonaws.com | https://ec2.ap-southeast-2.amazonaws.com | https://ec2.sa-east-1.amazonaws.com | OpenStack: https://192.168.0.1:8773/services/Cloud | Cloud(n) JP: https://comp-apia.jp-e1.cloudn-service.com/awsapi | Cloud(n) US: https://comp-apia.us-e1.cloudn-service.com/awsapi"),
      '#default_value' => $entity->endpoint(),
      '#maxlength'     => 255,
      '#required'      => TRUE,
    ];

    $form['region'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Region'),
      '#description'   => $this->t('us-east-1 | us-east-2 | us-west-1 | us-west-2 | ca-central-1 | eu-west-1 | eu-central-1 | eu-west-1 | ap-south-1 | ap-northeast-1 | ap-northeast-2 | ap-southeast-1 | ap-southeast-2 | sa-east-1 | Cloud(n) JP: jp-e1 | Cloud(n) US: us-e1'),
      '#default_value' => $entity->region(),
      '#maxlength'     => 255,
      '#required'      => TRUE,
    ];

    $form['aws_access_key'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Access Key'),
      '#description' => t('16-32 Characters, e.g. 12ABCDEFGHIJKVWXYZ89'),
      '#maxlength'     => 32,
      '#default_value' => $entity->aws_access_key(),
      '#required'      => TRUE,
    ];

    $form['aws_secret_key'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Secret Key'),
      '#description'   => $this->t('e.g. 123ABC/defGHIjkl34+LMNopq567RSTuvwxYz89Z'),
      '#maxlength'     => 55,
      '#default_value' => $entity->aws_secret_key(),
      '#required'      => TRUE,
    ];

    $form['user_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('User ID'),
      '#description'   => $this->t('e.g. 123456789012'),
      '#default_value' => $entity->user_id(),
      '#maxlength'     => 32,
      '#required'      => FALSE,
    ];

    $form['image_upload_url'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Image Upload URL'),
      '#description'     => $this->t('e.g. https://s3.amazonaws.com'),
      '#default_value' => $entity->image_upload_url(),
      '#maxlength'     => 255,
      '#required'      => FALSE,
    ];

    $form['image_register_url'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Image Register URL'),
      '#description' => $this->t("'https://' is REQUIRED: e.g. https://ec2.amazonaws.com | https://ec2.us-west-1.amazonaws.com | https://ec2.us-west-2.amazonaws.com | https://ec2.eu-west-1.amazonaws.com | https://ec2.ap-northeast-1.amazonaws.com | https://ec2.ap-southeast-1.amazonaws.com | https://ec2.ap-southeast-2.amazonaws.com | https://ec2.sa-east-1.amazonaws.com"),
      '#default_value' => $entity->image_register_url(),
      '#maxlength'     => 255,
      '#required'      => FALSE,
    ];

    $form['certificate'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('X.509 Certificate'),
      '#description'   => $this->t('X.509 Certificate to use. You can temporarily put dummy string if you do not use Bundle Image capability.'),
      '#default_value' => $entity->certificate(),
      '#cols'          => 17,
      '#rows'          => 66,
      '#required'      => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $cloud_context = $entity->isNew()
                   ? $form['cloud_context']['#value']
                   : $entity->cloud_context();

    // Check if ID exists.
    if (($entity->isNew() && $this->exist($cloud_context))) {
      $form_state->setError($form, $this->t('The %cloud_context already exists.', [
        '%cloud_context' => $cloud_context,
      ]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->entity;

    $cloud_context = $entity->isNew()
                   ? trim($form['cloud_context']['#value'])
                   : $entity->cloud_context();

    $entity->set('api_version'       , trim($form['api_version']['#value']));
    $entity->set('endpoint'          , trim($form['endpoint']['#value']));
    $entity->set('region'            , trim($form['region']['#value']));
    $entity->set('aws_access_key'    , trim($form['aws_access_key']['#value']));
    $entity->set('aws_secret_key'    , trim($form['aws_secret_key']['#value']));
    $entity->set('user_id'           , trim($form['user_id']['#value']));
    $entity->set('image_upload_url'  , trim($form['image_upload_url']['#value']));
    $entity->set('image_register_url', trim($form['image_register_url']['#value']));
    $entity->set('certificate'       , trim($form['certificate']['#value']));

    // When performing action - add.
    if ($entity->isNew() && isset($cloud_context)) {
      // The key: 'id' = 'cloud_context'.
      $entity->set('id', $cloud_context);
      $entity->set('created', time());
    }

    // When performing action - add or edit.
    $entity->set('changed', time());

    // TRUE or FALSE.
    if ($entity->save()) {
      drupal_set_message($this->t('AWS cloud information "%label" has been saved.', [
        '%label' => $entity->label(),
      ]));
    }
    else {
      drupal_set_message($this->t('AWS cloud information "%label" was not saved.', [
        '%label' => $entity->label(),
      ]));
    }

    $form_state->setRedirectUrl($entity->urlInfo('collection'));
  }

  /**
   * Helper function that gets a list of all the
   * subclouds defined and presents an array back
   * to the Add Cloud Form.
   */
  function _aws_cloud_base_cloud_options() {

    return [
      'amazon_ec2'     => 'Amazon EC2',
      'openstack_nova' => 'OpenStack Nova',
      'cloudn'         => 'Cloudn',
      'eucalyptus'     => 'Eucalyptus',
    ];

    // @TODO
    $data = aws_cloud_get_cloud_data();
    $return = [];

    foreach ($data as $key => $value) {

      $return[$key] = $value['name'];
    }

    return $return;
  }

}

