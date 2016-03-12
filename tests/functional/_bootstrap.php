<?php

$rabman = [
    'base_uri' => 'http://' . getenv('RABBIT_HOST'),
    'default' => [
        'auth' => ['guest', 'guest'],
    ],
];

\Codeception\Util\Fixtures::add('rabman-opt', $rabman);
