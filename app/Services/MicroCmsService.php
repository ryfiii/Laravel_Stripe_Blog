<?php

namespace App\Services;

use GuzzleHttp\Client;

class MicroCmsService
{
    protected $client;
    
    public function __construct(Client $client) {
        $this->client = new Client([
            'base_uri' => "https://stripeblog.microcms.io/api/v1/",
            'headers' => [
                'X-API-KEY' => config("services.microcms.apikey"),
            ],
        ]);
    }

    public function getBlog() {
        $response = $this->client->get('blogs');
        return json_decode($response->getBody(), true);
    }

    public function getBlogById($blogId) {
        $response = $this->client->get("blogs/{$blogId}");
        return json_decode($response->getBody(), true);
    }
}
