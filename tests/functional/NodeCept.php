<?php

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$items = $rabman->nodes()->columns(['exchange_types.name']);
$count = count($items);

$I->assertTrue($count > 0);

$expected = [
    ['name' => 'direct',],
    ['name' => 'headers',],
    ['name' => 'topic',],
    ['name' => 'fanout',],
];

$I->assertEquals($expected, $items[0]['exchange_types']);
