<?php

namespace Rabman\Resources;

interface ResourceVHostInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function vhost($name = '');
}
