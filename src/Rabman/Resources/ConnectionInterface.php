<?php

namespace Rabman\Resources;

interface ConnectionInterface extends ResourceInterface, ResourceDeleteInterface
{
    /**
     * @return $this
     */
    public function channels();
}
