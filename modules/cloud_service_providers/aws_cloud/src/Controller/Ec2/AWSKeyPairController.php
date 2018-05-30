<?php

namespace Drupal\aws_cloud\Controller\Ec2;

use Drupal\aws_cloud\Entity\Ec2\KeyPair;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AWSKeyPairController extends ControllerBase {

  /**
   * ApiController constructor.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *  Messanger Object
   */
  public function __construct(Messenger $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * Dependency Injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * @param String $cloud_context
   * @param \Drupal\aws_cloud\Entity\Ec2\KeyPair $aws_cloud_key_pair
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   */
  public function downloadKey($cloud_context, KeyPair $aws_cloud_key_pair) {
    $file = $aws_cloud_key_pair->getKeyFileLocation();
    if ($file != FALSE) {
      $response = new BinaryFileResponse($file, 200, [], FALSE, 'attachment');
      $response->setContentDisposition('attachment', $aws_cloud_key_pair->key_pair_name() . '.pem');
      $response->deleteFileAfterSend(TRUE);
      return $response;
    }
    else {
      // just redirect to keypair listing page.
      return $this->redirect('view.aws_cloud_key_pairs.page_1', [
        'cloud_context' => $cloud_context,
      ]);
    }
  }

}