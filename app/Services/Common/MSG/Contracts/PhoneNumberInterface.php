<?php

namespace App\Services\Common\MSG\Contracts;

/**
 * Interface PhoneNumberInterface
 *
 * @package App\Services\Common\MSG\Contracts
 */
interface PhoneNumberInterface
{

    /**
     * 获取号码 18888888888
     *
     * @return string
     */
    public function getNumber(): string;

    /**
     * 获取国际区号 +86
     *
     * @return int
     */
    public function getIDDCode(): int;

    /**
     * 获取国际号码 +8618888888888
     *
     * @return string
     */
    public function getUniversalNumber(): string;

    /**
     * 填充 0
     *
     * @return string
     */
    public function getZeroPreFixedNumber(): string;

    /**
     * @return string
     */
    public function __toString(): string;

}