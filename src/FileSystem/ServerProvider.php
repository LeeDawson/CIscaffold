<?php

namespace OutSource\Filesystem;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use OutSource\Filesystem\FileSystem;

class ServerProvider implements ServiceProviderInterface
{
    function register(Container $app)
    {
        $this->registerNativeFilesystem($app);
    }

    protected function registerNativeFilesystem($app)
    {
        $app['files'] = function ($app) {
            return new FileSystem($app['config']);
        };
    }
}