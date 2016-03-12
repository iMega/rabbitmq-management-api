<?php

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$items = $rabman->overview()->columns(['management_version']);

$count = count($items);

$I->assertTrue($count > 0);

$I->assertEquals('3.6.1', $items[0]['management_version']);
