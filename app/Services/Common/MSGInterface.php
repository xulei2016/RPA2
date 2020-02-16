<?php


namespace App\Services\Common;


abstract class MSGInterface
{

    protected $content;

    /**
     * @return bool
     */
    abstract protected function send();

}