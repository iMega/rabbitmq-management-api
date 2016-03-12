<?php

$fixture = require 'fixtures/definition.php';

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$items = $rabman->definitions();
$count = count($items);

$I->assertTrue($count > 0);

$rabman->definitions()->vhost()->create(json_decode($fixture, true));

$items = $rabman->exchanges('second.exchange.5')->columns(['name']);
count($items);
$I->assertTrue($count > 0);
$I->assertEquals('second.exchange.5', $items[0]['name']);
