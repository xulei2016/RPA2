<?php


namespace App\Services\Common\MSG\Strategies;

use App\Services\Common\MSG\Contracts\StrategyInterface;

/**
 * 随机策略
 * Class RandomStrategy
 *
 * @package App\Services\Common\MSG\Strategies
 */
class RandomStrategy implements StrategyInterface
{
    /**
     * 随机排序
     *
     * @param array $gateways
     * @return array
     */
    public function apply(array $gateways)
    {
        uasort($gateways, function () {
            return mt_rand() - mt_rand();
        });

        return array_keys($gateways);
    }
}