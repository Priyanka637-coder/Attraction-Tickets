<?php

namespace Drupal\attraction_tickets\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class MyClient
{
    /**
     * An http client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;
    /**
     * Hr Base URI.
     *
     * @var string
     */
    protected $base_uri;
    /**
     * Constructor.
     */
    public function __construct(ClientInterface $http_client)
    {
        $this->httpClient = $http_client;
        $this->base_uri = 'https://global.atdtravel.com';
    }
    /**
     * { @inheritdoc }
     */
    public function request($method, $endpoint, $query, $body)
    {
        try {
            $response = $this->httpClient->{$method}(
                $this->base_uri . $endpoint,
                $this->buildOptions($query, $body)
            );
        } catch (RequestException $exception) {
            return FALSE;
        }
        $headers = $response->getHeaders();
        return $response->getBody()->getContents();
    }

    private function buildOptions($query, $body)
    {
        $options = [];
        if ($body) {
            $options['body'] = $body;
        }
        if ($query) {
            $options['query'] = $query;
        }
        return $options;
    }
}
