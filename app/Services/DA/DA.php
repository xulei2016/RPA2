<?php


namespace App\Services\DA;

use App\Models\Admin\MDC\SysLoginRecord;

/**
 * Class DA 区域分析
 * @package App\Services\DA
 * @auther hsu lay
 * @since 20200111
 */
class DA extends DAInterface
{
    /**
     * login records id
     * @var $id
     */
    protected $id;

    /**
     * login data
     * @var $data
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function single_login(){

    }

    /**
     * @inheritDoc
     */
    protected function analyse(): int
    {

    }
}