<?php

namespace OutSource\Config;

use Pimple\Container;
use OutSource\Config\Config;
use Pimple\ServiceProviderInterface;


class ServerProvider implements ServiceProviderInterface
{
    /**
     * contain
     *
     * @var object
     */
    protected $container = null;

    public function register(Container $pimple)
    {
        $pimple['config'] = function($pimple){
            return new Config($pimple['user_configs']);
        };
        return $this;
    }


}