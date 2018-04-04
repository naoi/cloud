<?php

namespace Drupal\aws_cloud\Form\Ec2;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\aws_cloud\Controller\Ec2\ApiController;
use Drupal\aws_cloud\Entity\Config\Config;
use Drupal\Core\Messenger\MessengerInterface;

class ImageImportForm extends FormBase {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new UserLoginForm.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(RendererInterface $renderer, MessengerInterface $messenger) {
    $this->renderer = $renderer;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'image_import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cloud_context = '') {
    $form['markup'] = [
      '#markup' => $this->t('Use this form to import images into the system.  Only one field is needed for searching.  The import process can return a very large set of images.  Please try to be specific in your search.')
    ];
    $form['owners'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Owners'),
      '#description' => $this->t('Comma separated list of owners.  For example "self, amazon".  Specifying amazon will bring back around 4000 images, which is a rather large set of images.'),
    ];

    $form['image_ids'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image Ids'),
      '#description' => $this->t('Comma separated list of image ids'),
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search for images by ami name'),
      '#description' => $this->t('You can also use wildcards with the filter values. An asterisk (*) matches zero or more characters, and a question mark (?) matches exactly one character.  For example: *ubuntu-16.04* will bring back all images with name ubuntu-16.04 in the AMI name.'),
    ];

    $form['cloud_context'] = [
      '#type' => 'value',
      '#value' => $cloud_context,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = ['#type' => 'submit', '#value' => $this->t('Import')];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Build the Params array for importImages
    $params = [];
    $owners = $form_state->getValue('owners');
    if (!empty($owners)) {
      $params['Owners'] = explode(',', $owners);
    }
    $image_ids = $form_state->getValue('image_ids');
    if (!empty($image_ids)) {
      $params['ImageIds'] = explode(',', $image_ids);
    }

    $names = $form_state->getValue('name');
    if (!empty($names)) {
      $params['Filters'] = array(
        array(
          'Name' => 'name',
          'Values' => [$form_state->getValue('name')]
        ),
      );
    }

    $cloud_context = $form_state->getValue('cloud_context');
    // @TODO: the APIController call needs to be refactored
    $image_count = 0;

    if (count($params)) {
      $apiController = ApiController::create(\Drupal::getContainer());
      $image_count = $apiController->importImages(Config::load($cloud_context), $params);
    }

    $this->messenger->addMessage($this->t('Imported @count images', ['@count' => $image_count]));
    return $form_state->setRedirect('entity.aws_cloud_image.collection', [
      'cloud_context' => $cloud_context,
    ]);
  }

}