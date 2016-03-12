# Rabman

Description.

### Modificators

 * sort
 * columns
 * filter

You can also restrict what information is returned per item with the columns parameter.

```
$exchanges = $source->exchanges()->columns(['name', 'vhost']);

foreach ($exchanges as $item) {
    var_dump($item);
}
```

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
