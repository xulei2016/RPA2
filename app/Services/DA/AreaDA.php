<?php


namespace App\Services\DA;

use App\Models\Admin\MDC\SysLoginRecord;
use App\Services\DA\DA;

/**
 * Class AreaDA 区域分析
 * @package App\Services\DA
 * @auther hsu lay
 * @since 20200111
 */
class AreaDA extends DA
{

    /**
     * @var login records id
     */
    protected $id;

    /**
     * @var login data
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * @inheritDoc
     */
    protected function analyse(): int
    {

    }
}