<?php

namespace Rabman\Resources;

use Rabman\Modificator;
use Rabman\Client;

class Resource implements ResourceInterface, \ArrayAccess, \Iterator, \Countable
{
    use Modificator\Columns;
    use Modificator\Sort;

    protected $resource;
    protected $cursor = 0;
    protected $resourceOptions = [];
    protected $data = [];
    protected $endpoint = [];
    protected $queryOptions = [];

    /**
     * @var Client
     */
    protected $client;

    public function __construct($resource, array $options, Client $client = null)
    {
        $this->resource = $resource;
        $this->resourceOptions = $options;
        $this->client = $client ?: new Client();
    }

    protected function getBasic()
    {
        $this->endpoint['basic'] = $this->resourceOptions['basic'];
    }

    protected function getPath()
    {
        return $this->resourceOptions['path'];
    }

    public function columns(array $data)
    {
        $columns = ['query' => $this->columnsModificator($data)];
        $this->queryOptions = array_merge_recursive($this->queryOptions, $columns);

        return $this;
    }

    public function sort($fields, $reverse = false)
    {
        $sort = ['query' => $this->sortModificator($fields, $reverse)];
        $this->queryOptions = array_merge_recursive($this->queryOptions, $sort);

        return $this;
    }

    public function vhost($value = '%2f')
    {
        $this->endpoint['vhost'] = $value;

        return $this;
    }

    public function create(array $options)
    {
        $this->makePath();
        if (Type::DEFINITION == $this->resource) {
            $this->client->createWithPost($options);
        } else {
            $this->client->create($options);
        }
    }

    public function delete(array $options = [])
    {
        $this->makePath();
        $this->client->delete($options);
    }

    public function publish(array $options)
    {
        $this->endpoint['publish'] = 'publish';
        $this->makePath();
        return $this->client->publish($options);
    }

    public function contents()
    {
        $this->delete();
    }

    public function get(array $options)
    {
        $this->endpoint['get'] = 'get';
        $this->makePath();
        return $this->client->getMessages($options);
    }

    public function rewind()
    {
        if (empty($this->data)) {
            $this->makePath();
            $this->data = $this->client->get($this->queryOptions);
        } else {
            $this->cursor = 0;
        }
    }

    public function current()
    {
        return $this->data[$this->cursor];
    }

    public function key()
    {
        return $this->data;
    }

    public function next()
    {
        ++$this->cursor;
    }

    public function valid()
    {
        return isset($this->data[$this->cursor]);
    }

    public function count()
    {
        if (empty($this->data)) {
            $this->makePath();
            $this->data = $this->client->get($this->queryOptions);
        }

        return count($this->data);
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {}

    public function offsetUnset($offset)
    {}

    public function __call($method, $args = null)
    {
        $this->endpoint[$method] = $method;

        return $this;
    }

    protected function makePath()
    {
        $this->getBasic();
        $this->client->endpoint = array_merge($this->client->endpoint, $this->endpoint);
        $this->client->path = $this->getPath();
    }
}
