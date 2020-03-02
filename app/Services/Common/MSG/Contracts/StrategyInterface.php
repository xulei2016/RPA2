<?php


namespace App\Services\Common\MSG\Contracts;

/**
 * Interface StrategyInterface
 * @package App\Services\Common\MSG\Contracts
 */
interface StrategyInterface
{
    /**
     * Apply the strategy and return result.
     *
     * @param array $gateways
     *
     * @return array
     */
    public function apply(array $gateways);
}