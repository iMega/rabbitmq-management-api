<?php

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$items = $rabman->exchanges()->columns(['name'])->sort('name', true);
$count = count($items);

$I->assertTrue($count > 0);

$rabman->exchanges('second.ex.1')->vhost('second_virtual')->create(['type' => 'topic']);
$rabman->exchanges('second.ex.1')->vhost('second_virtual')->delete();
