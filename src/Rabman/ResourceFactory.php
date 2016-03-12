<?php

namespace Rabman;

use GuzzleHttp\Client as GuzzleClient;
use Rabman\Resources\Resource;
use Rabman\Resources\Type;

class ResourceFactory
{
    private static $resources = [
        Type::OVERVIEW => [
            'basic' => 'api/overview',
            'path'  => ['basic'],
        ],
        Type::CLUSTER => [
            'basic' => 'api/cluster-name',
            'path'  => ['basic'],
        ],
        Type::NODE => [
            'basic' => 'api/nodes',
            'path'  => ['basic', 'name'],
        ],
        Type::EXTENSION => [
            'basic' => 'api/extensions',
            'path'  => ['basic'],
        ],
        Type::DEFINITION => [
            'basic' => 'api/definitions',
            'path'  => ['basic', 'vhost'],
        ],
        Type::CONNECTION => [
            'basic' => 'api/connections',
            'path'  => ['basic', 'name', 'channels'],
        ],
        Type::CHANNEL => [
            'basic' => 'api/channels',
            'path'  => ['basic', 'name'],
        ],
        Type::CONSUMER => [
            'basic' => 'api/consumers',
            'path'  => ['basic', 'name'],
        ],
        Type::EXCHANGE => [
            'basic' => 'api/exchanges',
            'path'  => ['basic', 'vhost', 'name', 'bindings', 'source', 'destination', 'publish'],
        ],
        Type::QUEUE => [
            'basic' => 'api/queues',
            'path'  => ['basic', 'vhost', 'name', 'bindings', 'contents', 'actions', 'get'],
        ],
        Type::VHOST => [
            'basic' => 'api/vhosts',
            'path'  => ['basic', 'vhost', 'name', 'permissions'],
        ],
    ];

    private $client;

    public function __construct(array $options = [], GuzzleClient $guzzleClient = null)
    {
        $this->client = new Client($options, $guzzleClient);
    }

    /**
     * @return \Rabman\Resources\ResourceInterface
     */
    public function overview()
    {
        return $this->get(Type::OVERVIEW);
    }

    /**
     * @return \Rabman\Resources\ClusterInterface
     */
    public function cluster()
    {
        return $this->get(Type::CLUSTER);
    }

    /**
     * @param string $name
     *
     * @return \Rabman\Resources\ResourceInterface
     */
    public function nodes($name = '')
    {
        return $this->get(Type::NODE, $name);
    }

    /**
     * @return \Rabman\Resources\ResourceInterface
     */
    public function extensions()
    {
        return $this->get(Type::EXTENSION);
    }

    /**
     * @return \Rabman\Resources\DefinitionInterface
     */
    public function definitions()
    {
        return $this->get(Type::DEFINITION);
    }

    /**
     * @param string $name
     *
     * @return \Rabman\Resources\ConnectionInterface
     */
    public function connections($name = '')
    {
        return $this->get(Type::CONNECTION, $name);
    }

    /**
     * @param string $name
     *
     * @return \Rabman\Resources\ResourceInterface
     */
    public function channels($name = '')
    {
        return $this->get(Type::CHANNEL, $name);
    }

    /**
     * @return \Rabman\Resources\ConsumerInterface
     */
    public function consumers()
    {
        return $this->get(Type::CONSUMER);
    }

    /**
     * @param string $name
     *
     * @return \Rabman\Resources\ExchangeInterface
     */
    public function exchanges($name = '')
    {
        return $this->get(Type::EXCHANGE, $name);
    }

    /**
     * @param string $name
     *
     * @return \Rabman\Resources\QueueInterface
     */
    public function queues($name = '')
    {
        return $this->get(Type::QUEUE, $name);
    }

    /**
     * @return \Rabman\Resources\Users
     */
    public function users()
    {
        $source = $this->get('users');

        return $source;
    }

    public function vhosts($name = '%2f')
    {
        return $this->get(Type::VHOST, $name);
    }

    private function get($resource, $name = '')
    {
        if (!array_key_exists($resource, self::$resources)) {
            throw new \InvalidArgumentException(sprintf('The service "%s" is not available. Pick one among "%s".', $resource, implode('", "', array_keys(self::$resources))));
        }
        empty($name) ?: $this->client->endpoint['name'] = urlencode($name);

        return new Resource($resource, self::$resources[$resource], $this->client);
    }
}
