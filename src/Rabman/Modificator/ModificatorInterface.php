<?php

namespace Rabman\Modificator;

interface ModificatorInterface
{
    /**
     * @param array $data
     *
     * @return $this
     */
    public function columns(array $data);

    /**
     * @param            $fields
     * @param bool|false $reverse
     *
     * @return $this
     */
    public function sort($fields, $reverse = false);
}
