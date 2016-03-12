<?php

$rabman = [
    'base_uri' => 'http://rabbit-server:15672',
    'default' => [
        'auth' => ['guest', 'guest'],
    ],
];

\Codeception\Util\Fixtures::add('rabman-opt', $rabman);
