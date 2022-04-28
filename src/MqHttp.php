<?php

namespace Qoligo\Package;

use GuzzleHttp\Client as HttpClient;

class MqHttp {

    public function __construct()
    {
        $this->connection = new HttpClient();
    }

    public function publishMessage($event, $payload, $arguments = [])
    {
      try {
        $this->connection->request('POST', config('app.config_base_url') . config('app.config_producer_url'), [
            'form_params' => [
                'event' => $event,
                'payload' => $payload,
                'arguments' => $arguments
            ],
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Authorization' => config('app.authorization')
            ]
        ]);
      } catch (\Exception $e) {
        throw $e;
      }
    }
}