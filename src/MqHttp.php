<?php

namespace Qoligo\Package;

use GuzzleHttp\Client as HttpClient;

class MqHttp {

    public function __construct()
    {
        $this->connection = new HttpClient();
    }

    public function publishMessage($form_params, $env)
    {
      try {
        $this->connection->request('POST', $env['config_base_url'] . $env['config_producer_url'], [
            'form_params' => $form_params,
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => $env['authorization']
            ]
        ]);
      } catch (\Exception $e) {
        throw $e;
      }
    }
}