<?php

namespace Rabman\Modificator;

trait Columns
{
    protected function columnsModificator(array $data)
    {
        return ['columns' => implode(',', $data)];
    }
}
