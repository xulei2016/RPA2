<?php


namespace App\Services\DA;


abstract class DAInterface
{
    /**
     * @return int
     */
    abstract protected function analyse(): int;
}