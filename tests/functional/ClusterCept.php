<?php

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$rabman->cluster()->create(['name' => 'rabbit@my-rabbit']);

$items = $rabman->cluster();
$count = count($items);

$I->assertTrue($count > 0);
$I->assertEquals('rabbit@my-rabbit', $items[0]['name']);

$rabman->cluster()->create(['name' => 'rabbit@not-my-rabbit']);

$items = $rabman->cluster();
$count = count($items);

$I->assertTrue($count > 0);
$I->assertEquals('rabbit@not-my-rabbit', $items[0]['name']);
