<?php

namespace Rabman;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class Client
{
    /**
     * @var GuzzleClient
     */
    protected $client;
    public $path = [];
    public $endpoint = [];

    public function __construct(array $options = [], ClientInterface $client = null)
    {
        $options = array_replace(array(
            'base_uri' => 'http://localhost:15672',
            'auth' => ['guest', 'guest'],
        ), $options);

        $this->client = $client ?: new GuzzleClient($options);
    }

    public function get(array $options = [])
    {
        return $this->call(new Request('GET', $this->generateEndpoint()), $options);
    }

    public function create(array $options)
    {
        $this->cast(new Request('PUT', $this->generateEndpoint()), ['json' => $options]);
    }

    public function delete(array $options = [])
    {
        $opt = empty($options) ? [] : ['json' => $options];
        $this->cast(new Request('DELETE', $this->generateEndpoint()), $opt);
    }

    public function publish(array $options)
    {
        return $this->call(new Request('POST', $this->generateEndpoint()), ['json' => $options]);
    }

    public function createWithPost(array $options)
    {
        $this->cast(new Request('POST', $this->generateEndpoint()), ['json' => $options]);
    }

    public function getMessages(array $options)
    {
        return $this->call(new Request('POST', $this->generateEndpoint()), ['json' => $options]);
    }

    protected function call(RequestInterface $request, array $options = [])
    {
        $response = $this->client->send($request, $options);
        $data     = json_decode((string) $response->getBody(), true);

        return isset($data[0]) ? $data : [$data];
    }

    protected function cast(RequestInterface $request, array $options = [])
    {
        $this->client->send($request, $options);
    }

    protected function generateEndpoint()
    {
        $path     = '';
        $endpoint = $this->endpoint;
        array_walk(array_flip($this->path), function($v, $k) use (&$path, $endpoint) {
            $path .= isset($endpoint[$k]) ? $endpoint[$k] . '/' : '';
        });

        return substr($path, 0, -1);
    }
}
