# Rabman

A simple object oriented wrapper for the RabbitMQ Management HTTP Api.

## Table of Contents

 * [Modificators](#modificators)
 * [Overview](#overview)
 * [Cluster](#cluster)
 * [Nodes](#nodes)
 * [Extensions](#extensions)
 * [Definition](#definition)
 * [Connections](#connections)
 * [Exchanges](#exchanges)
 * [Queues](#queues)

### Modificators

 * sort
 * columns

`Sort` allows you to select a primary field to sort by, and second paramenter will reverse the sort order if set to true. The sort first parameter can contain subfields separated by dots. This allows you to sort by a nested component of the listed items; it does not allow you to sort by more than one field. See the example below.

```
$exchanges = $source->exchanges()->columns(['name'])->sort('name', true);
foreach ($exchanges as $item) {
    var_dump($item);
}
```

You can also restrict what information is returned per item with the columns parameter.

```
$exchanges = $source->exchanges()->columns(['name', 'vhost']);
foreach ($exchanges as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Overview

Various random bits of information that describe the whole system.

```
$overview = $source->overview()->columns(['exchange_types.name']);
foreach ($exchanges as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Cluster

Name identifying this RabbitMQ cluster.

```
$cluster = $source->cluster();
foreach ($cluster as $item) {
    var_dump($item);
}
```

and rename cluster
```
$source->cluster()->create(['name' => 'rabbit@my-rabbit']);
```

[Back to TOC](#table-of-contents)

### Nodes

A list of nodes in the RabbitMQ cluster.

```
$nodes = $source->nodes();
foreach ($nodes as $item) {
    var_dump($item);
}
```

An individual node in the RabbitMQ cluster.

```
$nodes = $source->nodes('my-node');
foreach ($nodes as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Extensions

A list of extensions to the management plugin.

```
$extensions = $source->extensions();
foreach ($extensions as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Definition

The server definitions - exchanges, queues, bindings, users, virtual hosts, permissions and parameters. Everything apart from messages.

```
$items = $source->definitions();
foreach ($items as $item) {
    var_dump($item);
}
```

Create to upload an existing set of definitions. Note that:

 * The definitions are merged. Anything already existing on the server but not in the uploaded definitions is untouched.
 * Conflicting definitions on immutable objects (exchanges, queues and bindings) will cause an error.
 * Conflicting definitions on mutable objects will cause the object in the server to be overwritten with the object from the definitions.
 * In the event of an error you will be left with a part-applied set of definitions.

```
$source->definitions()->create(<very-very-big-array>);
```

The server definitions for a given virtual host - exchanges, queues, bindings and policies.

```
$items = $source->definitions()->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

Create to upload an existing set of definitions for a given virtual host. Note that:

 * The definitions are merged. Anything already existing on the server but not in the uploaded definitions is untouched.
 * Conflicting definitions on immutable objects (exchanges, queues and bindings) will cause an error.
 * Conflicting definitions on mutable objects will cause the object in the server to be overwritten with the object from the definitions.
 * In the event of an error you will be left with a part-applied set of definitions.

```
$source->definitions()->vhost('second_virtual_host')->create(<very-very-big-array>);
```

[Back to TOC](#table-of-contents)

### Connections

A list of all open connections.

```
$connections = $source->connections();
foreach ($connections as $item) {
    var_dump($item);
}
```

A list of all open connections in a specific vhost.
```
$connections = $source->vhosts('second_virtual_host')->connections();
foreach ($connections as $item) {
    var_dump($item);
}
```

An individual connection.

```
$connections = $source->connections("127.0.0.1:35003 -> 127.0.0.1:5672");
foreach ($connections as $item) {
    var_dump($item);
}
```

DELETEing it will close the connection.
```
$source->connections("127.0.0.1:35003 -> 127.0.0.1:5672")->delete();
```

List of all channels for a given connection.

```
$connections = $source->connections("127.0.0.1:35003 -> 127.0.0.1:5672")->channels();
foreach ($connections as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Exchanges

A list of all exchanges.

```
$exchanges = $source->exchanges();
foreach ($exchanges as $item) {
    var_dump($item);
}
```

A list of all exchanges in a given virtual host.

```
$exchanges = $source->exchanges()->vhost('second_virtual_host');
foreach ($exchanges as $item) {
    var_dump($item);
}
```

An individual exchange.

```
$exchanges = $source->exchanges('second.exchange')->vhost('second_virtual_host');
foreach ($exchanges as $item) {
    var_dump($item);
}
```

Create an exchange.

```
$source->exchanges('example.exchange')->vhost('second_virtual_host')->create([
    'type' => 'topic',
]);
```

When DELETEing an exchange you can add the options 'if-unused' => true. This prevents the delete from succeeding if the exchange is bound to a queue or as a source to another exchange.

```
$source->exchanges('example.exchange')->vhost('second_virtual_host')->delete([
    'if-unused' => true
]);
```

A list of all bindings in which a given exchange is the source.

```
$exchanges = $source->exchanges('second.exchange')->vhost('second_virtual_host')->bindings()->source();
foreach ($exchanges as $item) {
    var_dump($item);
}
```

A list of all bindings in which a given exchange is the destination.

```
$exchanges = $source->exchanges('second.exchange')->vhost('second_virtual_host')->bindings()->destination();
foreach ($exchanges as $item) {
    var_dump($item);
}
```

Publish a message to a given exchange. You will need the options looking something like:

```
$source->exchanges('second.exchange')->vhost('second_virtual_host')->publish([
    "properties"       => {},
    "routing_key"      => "my key",
    "payload"          => "my body",
    "payload_encoding" => "string",
]);
```
`"routed" => true` routed will be true if the message was sent to at least one queue.

### Queues

Contents of a queue to purge.
```
$source->queues('example.queues')->contents();
$source->queues('example.queues')->vhost('second_virtual_host')->contents();
```
