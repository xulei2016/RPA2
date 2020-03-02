<?php


namespace App\Services\Common\MSG;

use App\Services\Common\MSG\Contracts\PhoneNumberInterface;

/**
 * Class PhoneNumber
 * @package App\Services\Common\MSG
 */
class PhoneNumber implements PhoneNumberInterface
{
    /**
     * @var int
     */
    protected $number;

    /**
     * @var int
     */
    protected $IDDCode;

    /**
     * PhoneNumberInterface constructor.
     *
     * @param int    $numberWithoutIDDCode
     * @param string $IDDCode
     */
    public function __construct($numberWithoutIDDCode, $IDDCode = null)
    {
        $this->number = $numberWithoutIDDCode;
        $this->IDDCode = $IDDCode ? intval(ltrim($IDDCode, '+0')) : null;
    }

    /**
     * 获取号码
     *
     * @inheritDoc
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * 获取国际号段 +86
     *
     * @inheritDoc
     */
    public function getIDDCode(): int
    {
        return $this->IDDCode;
    }

    /**
     * 获取国际号码 +8618888888888
     *
     * @inheritDoc
     */
    public function getUniversalNumber(): string
    {
        return $this->getPrefixedIDDCode('+').$this->number;
    }

    /**
     * 获取异地号码 0018888888888
     *
     * @inheritDoc
     */
    public function getZeroPreFixedNumber(): string
    {
        return $this->getPrefixedIDDCode('00').$this->number;
    }

    /**
     * @param string $prefix
     *
     * @return string|null
     */
    public function getPrefixedIDDCode($prefix)
    {
        return $this->IDDCode ? $prefix.$this->IDDCode : null;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getUniversalNumber();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getUniversalNumber();
    }
}