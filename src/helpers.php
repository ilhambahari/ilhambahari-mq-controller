<?php

namespace Qoligo\Package;

use GuzzleHttp\Client as HttpClient;

class MqHttp {
    public function __construct()
    {
        $this->connection = new HttpClient();
    }

    public function publishMessage($form_params, $config_base_url, $config_producer_url, $authorization)
    {
      try {
        $this->connection->request('POST', $config_base_url . $config_producer_url, [
            'form_params' => $form_params,
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => $authorization
            ]
        ]);
      } catch (\Exception $e) {
        throw $e;
      }
    }
}