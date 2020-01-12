<?php


namespace App\Services\DA;


abstract class DA
{
    /**
     * @return int
     */
    abstract protected function analyse(): int;
}