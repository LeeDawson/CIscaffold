<?php

namespace OutSource\Kernel;

use Closure;
use Pimple\Container;
use OutSource\Config\Config;

class Application extends Container
{
    /**
     * 核心启动项目
     *
     * \OutSource\Console\ServerProvider::class  console
     */
    public $baseProviders = [
        \OutSource\Config\ServerProvider::class,
        \OutSource\FileSystem\ServerProvider::class,
        \OutSource\Console\ServerProvider::class,
    ];

    public $applicationPath;

    public function __construct(array $config = [])
    {
        parent::__construct();
        $this->setApplicationPath();
        $this->registerConfig($config);
        $this->registerProviders();
    }

    protected function setApplicationPath()
    {
        $this->applicationPath = dirname(__DIR__);
        $this['applicationPath'] = $this->applicationPath;
    }

    /**
     * Register service providers.
     *
     * @return $this
     */
    protected function registerProviders()
    {
        foreach ($this->baseProviders as $baseProvider) {
            $this->register(new $baseProvider($this));
        }
        return $this;
    }

    /**
     * Register config.
     *
     * @param array $config
     *
     * @return $this
     */
    protected function registerConfig(array $config)
    {
        $this['user_configs'] = array_merge($config, [ "applicationPath" => $this->applicationPath]);
        return $this;
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    public function run()
    {
        $application = $this['console'];
        $application->run();
    }



}