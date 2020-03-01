<?php


namespace App\Services\Common\MSG;

use App\Services\Common\MSG\Contracts\GatewayInterface;
use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Contracts\StrategyInterface;
use App\Services\Common\MSG\Exceptions\InvalidArgumentException;
use App\Services\Common\MSG\Exceptions\NoGatewayAvailableException;
use App\Services\Common\MSG\Gateways\Gateway;
use App\Services\Common\MSG\Storage\LogStorage;
use App\Services\Common\MSG\Strategies\OrderStrategy;
use App\Services\Common\MSG\Support\Config;
use Closure;
use RuntimeException;

/**
 * Class SMS
 *
 * @package App\Services\Common\MSG
 */
class SMS
{
    /**
     * @var config
     */
    protected $config;

    /**
     * @var string
     */
    protected $defaultGateway;

    /**
     * @var array
     */
    protected $customCreators = [];

    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * @var Messenger
     */
    protected $messenger;

    /**
     * @var array
     */
    protected $Strategy = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config = $config ?: config('sms');
        $this->config = empty($config) ?: new Config($config);

        if (!empty($config['default'])) {
            $this->setDefaultGateway($config['default']);
        }
    }

    /**
     * @param $to
     * @param $message
     * @param array $params
     * @param string $gateways
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function send($to, $message, array $params, $gateways = '')
    {
        $to = $this->formatPhoneNumber($to);
        $message = $this->formatMessage($message);
        $gateways = $gateways ? $message->getGatewaysFromDB($gateways) : [];

        if (empty($gateways)) {
            $gateways = $this->config->get('default.gateways', []);
        }

        $log = new LogStorage($this->config, $params);

        return $log->afterSend($log->beforeSend($to, $message), $this->getResponse($to, $message, $this->formatGateways($gateways), $this->config->get('debug', false)));
    }

    /**
     * @param null $strategy
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function strategy($strategy = null)
    {
        if (is_null($strategy)) {
            $strategy = $this->config->get('default.strategy', OrderStrategy::class);
        }

        if (!class_exists($strategy)) {
            $strategy = __NAMESPACE__ . '\Strategy\\' . ucfirst($strategy);
        }

        if (!class_exists($strategy)) {
            throw new InvalidArgumentException("Unsupported strategy \"{$strategy}\"");
        }

        if (empty($this->Strategy[$strategy]) || !($this->Strategy[$strategy] instanceof StrategyInterface)) {
            $this->Strategy[$strategy] = new $strategy($this);
        }

        return $this->Strategy[$strategy];
    }

    /**
     * @param $number
     * @return PhoneNumberInterface
     */
    protected function formatPhoneNumber($number)
    {
        if ($number instanceof PhoneNumberInterface) {
            return $number;
        }

        return new PhoneNumber(trim($number));
    }

    /**
     * @param $message
     * @return Message|array
     */
    protected function formatMessage($message)
    {
        if (!($message instanceof MessageInterface)) {
            if (!is_array($message)) {
                $message = [
                    'content' => $message,
                    'template' => $message,
                ];
            }

            $message = new Message($message);
        }

        return $message;
    }

    /**
     * @param array $gateways
     * @return array
     * @throws InvalidArgumentException
     */
    protected function formatGateways(array $gateways)
    {
        $formatted = [];

        foreach ($gateways as $gateway => $setting) {
            if (is_int($gateway) && is_string($setting)) {
                $gateway = $setting;
                $setting = [];
            }

            $formatted[$gateway] = $setting;
            $globalSettings = $this->config->get("gateways.{$gateway}", []);

            if (is_string($gateway) && !empty($globalSettings) && is_array($setting)) {
                $formatted[$gateway] = new Config(array_merge($globalSettings, $setting));
            }
        }

        $result = [];

        foreach ($this->strategy()->apply($formatted) as $name) {
            $result[$name] = $formatted[$name];
        }

        return $result;
    }

    /**
     * @param null $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function gateway($name = null)
    {
        $name = $name ?: $this->getDefaultGateway();

        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string $name
     * @param Closure $callback
     *
     * @return $this
     */
    public function extend($name, Closure $callback)
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }

    /**
     * @return config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getDefaultGateway()
    {
        if (empty($this->defaultGateway)) {
            throw new RuntimeException('No default gateway configured.');
        }

        return $this->defaultGateway;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setDefaultGateway($name)
    {
        $this->defaultGateway = $name;

        return $this;
    }

    /**
     * @return Messenger
     */
    public function getMessenger()
    {
        return $this->messenger ?: $this->messenger = new Messenger($this);
    }

    /**
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function createGateway($name)
    {
        if (isset($this->customCreators[$name])) {
            $gateway = $this->callCustomCreator($name);
        } else {
            $className = $this->formatGatewayClassName($name);
            $config = $this->config->get("gateways.{$name}", []);

            if (!isset($config['timeout'])) {
                $config['timeout'] = $this->config->get('timeout', Gateway::DEFAULT_TIMEOUT);
            }

            $gateway = $this->makeGateway($className, $config);
        }

        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(\sprintf('Gateway "%s" must implement interface %s.', $name, GatewayInterface::class));
        }

        return $gateway;
    }

    /**
     * @param $gateway
     * @param $config
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function makeGateway($gateway, $config)
    {
        if (!\class_exists($gateway) || !\in_array(GatewayInterface::class, \class_implements($gateway))) {
            throw new InvalidArgumentException(\sprintf('Class "%s" is a invalid easy-sms gateway.', $gateway));
        }

        return new $gateway($config);
    }

    /**
     * Format gateway name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function formatGatewayClassName($name)
    {
        if (\class_exists($name) && \in_array(GatewayInterface::class, \class_implements($name))) {
            return $name;
        }

        $name = \ucfirst(\str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__ . "\\Gateways\\{$name}Gateway";
    }

    /**
     * Call a custom gateway creator.
     *
     * @param string $gateway
     *
     * @return mixed
     */
    protected function callCustomCreator($gateway)
    {
        return \call_user_func($this->customCreators[$gateway], $this->config->get("gateways.{$gateway}", []));
    }

    /**
     * @param $to
     * @param $message
     * @param $gateways
     * @param $debug
     * @return array|mixed
     */
    protected function getResponse($to, $message, $gateways, $debug = false)
    {
        try {
            return $this->getMessenger()->send($to, $message, $gateways, $debug);
        } catch (NoGatewayAvailableException $e) {
            return $e->getResults();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}