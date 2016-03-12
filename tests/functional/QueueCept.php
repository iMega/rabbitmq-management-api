<?php

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$items = $rabman->queues();
$count = count($items);

$I->assertTrue($count > 0);

/*$queues = $source->queues('queue.billing.test')->vhost()->get([
    "count"    => 5,
    "requeue"  => true,
    "encoding" => "auto",
]);*/
