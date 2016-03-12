<?php

namespace Rabman\Modificator;

trait Sort
{
    protected function sortModificator($data, $reverse = false)
    {
        return [
            'sort'         => $data,
            'sort_reverse' => empty($reverse) ? "false" : "true",
        ];
    }
}
