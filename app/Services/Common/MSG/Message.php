<?php


namespace App\Services\Common\MSG;

use App\Services\Common\MSG\Contracts\GatewayInterface;
use App\Services\Common\MSG\Contracts\MessageInterface;

class Message implements MessageInterface
{
    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Message constructor.
     *
     * @param array $attributes
     * @param string $type
     */
    public function __construct(array $attributes = [], $type = MessageInterface::TEXT_MESSAGE)
    {
        $this->type = $type;

        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * 获取消息类型
     *
     * @return string
     */
    public function getMessageType(): string
    {
        return $this->type;
    }

    /**
     * 获取消息内容
     *
     * @param GatewayInterface|null $gateway
     * @return string
     */
    public function getContent(GatewayInterface $gateway = null): string
    {
        return is_callable($this->content) ? call_user_func($this->content, $gateway) : $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * 模板变量，使用在以模板ID来发送短信的平台
     *
     * @param GatewayInterface|null $gateway
     * @return array
     */
    public function getData(GatewayInterface $gateway = null): array
    {
        return is_callable($this->data) ? call_user_func($this->data, $gateway) : $this->data;
    }

    /**
     * @param array|callable $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param GatewayInterface|null $gateway
     * @return string
     */
    public function getTemplate(GatewayInterface $gateway = null): string
    {
        return is_callable($this->template) ? call_user_func($this->template, $gateway) : $this->template;
    }

    /**
     * @param mixed $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return array
     */
    public function getGateways(): array
    {
        return $this->gateways;
    }

    /**
     * @param array $gateways
     *
     * @return $this
     */
    public function setGateways(array $gateways)
    {
        $this->gateways = $gateways;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param $property
     *
     * @return string
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

}