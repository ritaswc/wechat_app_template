<?php

namespace app\hejiang;

use \Raven_Client as SentryClient;
use \Raven_ErrorHandler as SentryHandler;

/**
 * Sentry adapter
 *
 * @property SentryClient $client
 * @property SentryHandler $handler
 */
class Sentry extends \yii\base\Component
{
    /**
     * Sentry raven client
     *
     * @var SentryClient
     */
    protected $_client;

    /**
     * Error handler
     *
     * @var SentryHandler
     */
    protected $_handler;

    /**
     * Sentry options
     *
     * @var array|string
     */
    public $options;

    public function init()
    {
        YII_DEBUG ?: $this->install();
    }

    public function getClient()
    {
        return $this->_client ?: $this->_client = new SentryClient($this->options);
    }

    public function getHandler()
    {
        return $this->_handler;
    }

    public function getBreadcrumbs()
    {
        return $this->clinet->breadcrumbs;
    }

    protected function install()
    {
        $this->_handler = new SentryHandler($this->client);
        $this->_handler->registerExceptionHandler(true);
        $this->_handler->registerErrorHandler();
        $this->_handler->registerShutdownFunction();
    }

    public function __call($name, $params)
    {
        if (method_exists($this->client, $name)) {
            return call_user_func_array([$this->client, $name], $params);
        }
        return parent::__call($name, $params);
    }
}
