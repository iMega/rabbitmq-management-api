<?php

$I = new FunctionalTester($scenario);

$rabman = new \Rabman\ResourceFactory(\Codeception\Util\Fixtures::get('rabman-opt'));

$items = $rabman->extensions();
$count = count($items);

$I->assertTrue($count > 0);

$expected = [
    'javascript' => 'dispatcher.js',
];

$I->assertEquals($expected, $items[0]);
