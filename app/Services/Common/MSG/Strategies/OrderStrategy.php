<?php


namespace App\Services\Common\MSG\Strategies;

use App\Services\Common\MSG\Contracts\StrategyInterface;

/**
 * 顺序调用列表策略
 * Class OrderStrategy
 *
 * @package App\Services\Common\MSG\Strategies
 */
class OrderStrategy implements StrategyInterface
{
    /**
     * @param array $gateways
     * @return array
     */
    public function apply(array $gateways)
    {
        return array_keys($gateways);
    }
}