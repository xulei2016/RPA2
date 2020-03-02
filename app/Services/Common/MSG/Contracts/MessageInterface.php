<?php


namespace App\Services\Common\MSG\Contracts;

/**
 * Interface MessageInterface
 *
 * @package App\Services\Common\MSG\Contracts
 */
interface MessageInterface
{
    const TEXT_MESSAGE = 'text';

    const VOICE_MESSAGE = 'voice';

    /**
     * 获取消息类型
     *
     * @return string
     */
    public function getMessageType(): string;

    /**
     * 获取消息内容
     *
     * @param GatewayInterface|null $gateway
     * @return string
     */
    public function getContent(GatewayInterface $gateway = null): string;

    /**
     * 获取数据
     *
     * @param GatewayInterface|null $gateway
     * @return array
     */
    public function getData(GatewayInterface $gateway = null): array;

    /**
     * 获取模板
     *
     * @param GatewayInterface|null $gateway
     * @return string
     */
    public function getTemplate(GatewayInterface $gateway = null): string;

    /**
     * 获取支持消息的网关
     *
     * @return array
     */
    public function getGateways(): array;
}