<?php

namespace Drupal\relaxed\Plugin\RemoteCheck;

use Drupal\relaxed\Entity\RemoteInterface;
use Drupal\relaxed\Plugin\RemoteCheckBase;

/**
 * @RemoteCheck(
 *   id = "remote_register",
 *   label = "Register on remote"
 * )
 */
Class RemoteRegister extends RemoteCheckBase {

  /**
   * {@inheritdoc}
   */
  public function execute(RemoteInterface $remote) {
    $url = (string) $remote->uri();
    $client = \Drupal::httpClient();
    try {
      $response = $client->request('POST', $url . '/_remote-registration');

      if ($response->getStatusCode() === 200) {
        $this->result = TRUE;
        $this->message = t('Registration on remote is valid.');
      }
      else {
        $this->message = t('Remote returns status code @status.', ['@status' => $response->getStatusCode()]);
      }
    }
    catch (\Exception $e) {
      $this->message = $e->getMessage();
      watchdog_exception('relaxed', $e);
    }
  }

  /**
   * Collects the data necessary for registration on remote.
   */
  public function generateRegistrationPayload() {
    // Site name, Site domain, IP

    // Synchronisation taxonomy
  }
}
