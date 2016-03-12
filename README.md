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
 * [Channels](#channels)
 * [Consumers](#consumers)
 * [Exchanges](#exchanges)
 * [Queues](#queues)
 * [Bindings](#bindings)
 * [Vhosts](#vhosts)
 * [Users](#users)
 * [Whoami](#whoami)
 * [Permissions](#permissions)
 * [Parameters](#parameter)
 * [Policies](#policies)
 * [Aliveness](#aliveness)
 * [License](#license)

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

### Channels

A list of all open channels.

```
$items = $source->channels();
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all open channels in a specific vhost.

```
$items = $source->vhosts('second_virtual_host')->channels();
foreach ($items as $item) {
    var_dump($item);
}
```

Details about an individual channel.

```
$items = $source->channels('channnel');
foreach ($items as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Consumers

A list of all consumers.

```
$items = $source->consumers();
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all consumers in a given virtual host.

```
$items = $source->consumers()->vhost('second_virtual_host');
foreach ($items as $item) {
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

[Back to TOC](#table-of-contents)

### Queues

A list of all queues.

```
$items = $source->queues();
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all queues in a given virtual host.

```
$items = $source->queues()->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

An individual queue.

```
$items = $source->queues('name-queue')->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

To create a queue, you will need the parameters looking something like this (All keys are optional): 

```
$source->queues('name-queue')->vhost('second_virtual_host')->create([
    "auto_delete" => false,
    "durable" => true,
]);
```

When DELETEing a queue you can add the query string parameters if-empty=true and / or if-unused=true. These prevent the delete from succeeding if the queue contains messages, or has consumers, respectively.

```
$source->queues('name-queue')->vhost('second_virtual_host')->delete([
    "if-empty" => true,
]);
```

A list of all bindings on a given queue.

```
$items = $source->queues('name-queue')->vhost('second_virtual_host')->bindings();
foreach ($items as $item) {
    var_dump($item);
}
```

Contents of a queue to purge.

```
$source->queues('example.queues')->vhost('second_virtual_host')->contents();
```

Actions that can be taken on a queue.

```
$source->queues('example.queues')->vhost('second_virtual_host')->actions([
	"action" => "sync"
]);
```
Currently the actions which are supported are sync and cancel_sync.

Get messages from a queue. 

```
$items = $source->queues('name-queue')->vhost('second_virtual_host')->get([
    "count"    => 5,
    "requeue"  => true,
    "encoding" => "auto",
]);
foreach ($items as $item) {
    var_dump($item);
}
```
 * count controls the maximum number of messages to get. You may get fewer messages than this if the queue cannot immediately provide them.
 * requeue determines whether the messages will be removed from the queue. If requeue is true they will be requeued - but their redelivered flag will be set.
 * encoding must be either "auto" (in which case the payload will be returned as a string if it is valid UTF-8, and base64 encoded otherwise), or "base64" (in which case the payload will always be base64 encoded).
 * If truncate is present it will truncate the message payload if it is larger than the size given (in bytes).

truncate is optional; all other keys are mandatory.

Please note that the get path in the HTTP API is intended for diagnostics etc - it does not implement reliable delivery and so should be treated as a sysadmin's tool rather than a general API for messaging. 

[Back to TOC](#table-of-contents)

### Bindings

A list of all bindings.

```
$items = $source->bindings();
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all bindings in a given virtual host.

```
$items = $source->bindings()->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all bindings between an exchange and a queue.

```
$items = $source->bindings()->vhost('second_virtual_host')->exchange('name')->queue('name');
foreach ($items as $item) {
    var_dump($item);
}
```

Remember, an exchange and a queue can be bound together many times! To create a new binding, POST to this URI. You will need a body looking something like this: 

```
$source->bindings()->vhost('second_virtual_host')->exchange('name')->queue('name')->create([
    "routing_key" => "my_routing_key",
    "arguments"   => [],
]);
```

An individual binding between an exchange and a queue. The props is a "name" for the binding composed of its routing key and a hash of its arguments.

```
$items = $source->bindings('props')->vhost('second_virtual_host')->exchange('name')->queue('name');
foreach ($items as $item) {
    var_dump($item);
}
```

and delete the binding

```
$source->bindings('props')->vhost('second_virtual_host')->exchange('name')->queue('name')->delete();
```

A list of all bindings between two exchanges. Similar to the list of all bindings between an exchange and a queue, above.

```
$items = $source->bindings()->vhost('second_virtual_host')->source('name')->destination('name');
foreach ($items as $item) {
    var_dump($item);
}
```

An individual binding between two exchanges. Similar to the individual binding between an exchange and a queue, above. The props is a "name" for the binding composed of its routing key and a hash of its arguments.

```
$items = $source->bindings('props')->vhost('second_virtual_host')->source('name')->destination('name');
foreach ($items as $item) {
    var_dump($item);
}
```

and delete

```
$source->bindings('props')->vhost('second_virtual_host')->source('name')->destination('name')->delete();
```

[Back to TOC](#table-of-contents)

### Vhosts

A list of all vhosts.

```
$items = $source->vhosts();
foreach ($items as $item) {
    var_dump($item);
}
```

An individual virtual host.

```
$items = $source->vhosts('name');
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all permissions for a given virtual host.

```
$items = $source->vhosts('name')->permissions();
foreach ($items as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Users

A list of all users.

```
$items = $source->users();
foreach ($items as $item) {
    var_dump($item);
}
```

An individual user.

```
$items = $source->users('name');
foreach ($items as $item) {
    var_dump($item);
}
```

To create a user, you will need the parameters looking something like this:

```
$source->users('name')->create([
    "password" => "secret",
    "tags"     => "administrator",
]);
```

or

```
$source->users('name')->create([
    "password_hash" => "2lmoth8l4H0DViLaK9Fxi6l9ds8=",
    "tags"          => "administrator",
]);
```

The tags key is mandatory. Either password or password_hash must be set. Setting password_hash to "" will ensure the user cannot use a password to log in. tags is a comma-separated list of tags for the user. Currently recognised tags are "administrator", "monitoring" and "management". 

and delete a user

```
$source->users('name')->delete();
```

A list of all permissions for a given user.

```
$items = $source->users('name')->permissions();
foreach ($items as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Whoami

Details of the currently authenticated user.

```
$items = $source->whoami();
foreach ($items as $item) {
    var_dump($item);
}
```

[Back to TOC](#table-of-contents)

### Permissions

A list of all permissions for all users.

```
$items = $source->permissions();
foreach ($items as $item) {
    var_dump($item);
}
```

An individual permission of a user and virtual host.

```
$items = $source->permissions()->vhost('second_virtual_host')->user('name');
foreach ($items as $item) {
    var_dump($item);
}
```

To create a permission, you will need the parameters looking something like this (All keys are mandatory):

```
$source->permissions()->vhost('second_virtual_host')->user('name')->create([
    "configure" => ".*",
    "write"     => ".*",
    "read"      => ".*",
]);
```

and delete a user

```
$source->permissions()->vhost('second_virtual_host')->user('name')->delete();
```

[Back to TOC](#table-of-contents)

### Parameters

A list of all parameters.

```
$items = $source->parameters();
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all parameters for a given component.

```
$items = $source->parameters()->component('name');
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all parameters for a given component and virtual host.

```
$items = $source->parameters()->vhost('second_virtual_host')->component('name');
foreach ($items as $item) {
    var_dump($item);
}
```

An individual parameter.

```
$items = $source->parameters('parameter-name')->vhost('second_virtual_host')->component('component-name');
foreach ($items as $item) {
    var_dump($item);
}
```

To create a parameter, you will need the parameters looking something like this: 

```
$source->parameters('parameter-name')->vhost('second_virtual_host')->component('component-name')->create([
    "vhost"     => "/",
    "component" => "federation",
    "name"      => "local_username",
    "value"     => "guest",
]);
```

and delete a parameter

```
$source->parameters('parameter-name')->vhost('second_virtual_host')->component('component-name')->delete();
```

[Back to TOC](#table-of-contents)

### Policies

A list of all policies.

```
$items = $source->policies();
foreach ($items as $item) {
    var_dump($item);
}
```

A list of all policies in a given virtual host.

```
$items = $source->policies()->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

An individual policy.

```
$items = $source->policies('name-policy')->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

To create a policy, you will need the parameters looking something like this: 

```
$source->policies('name-policy')->vhost('second_virtual_host')->create([
    "pattern"    => "^amq.",
    "definition" => [
        "federation-upstream-set" => "all"
    ],
    "priority"   => 0,
    "apply-to"   => "all",
]);
```

and delete a policy

```
$source->policies('name-policy')->vhost('second_virtual_host')->delete();
```

[Back to TOC](#table-of-contents)

### Aliveness

Declares a test queue, then publishes and consumes a message. Intended for use by monitoring tools. If everything is working correctly, will return `"status" => "ok"`

```
$items = $source->aliveness()->vhost('second_virtual_host');
foreach ($items as $item) {
    var_dump($item);
}
```

Note: the test queue will not be deleted (to to prevent queue churn if this is repeatedly pinged). 

[Back to TOC](#table-of-contents)

### License

The MIT License (MIT)

Copyright (c) 2015 Dmitry Gavrilov <info@imega.ru>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
