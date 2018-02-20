<?php

namespace OutSource\Console;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use OutSource\Console\Application;


class ServerProvider implements ServiceProviderInterface
{
    /**
     * contain
     *
     * @var object
     */
    protected static $container = null;


    protected $commands = [

    ];

    function register(Container $pimple)
    {
        $this->initGenerator($pimple);
        return $pimple['console'] = function () use ($pimple) {
            return  $this->initKernlConsole($pimple);
        };

    }

    /**
     * load kernel commands
     *
     */
    public function initKernlConsole($pimple)
    {
        $this->addCommands($this->commands, $pimple);
        return $this->getContainer();
    }



    protected function addCommands($commands , $pimple)
    {
        foreach ($commands as $command) {
            $this->getContainer()->add(new $command($pimple));
        }
    }

    protected function getContainer()
    {
        if(! self::$container instanceof Application) {
            self::$container = new Application();
        }

        return self::$container;
    }

    protected function initGenerator($pimple)
    {
        $dirver = $pimple['config']['driver'];
        call_user_func([$this , "load".$dirver."Generators"], $pimple);
        call_user_func([$this , "prepare".$dirver."Commands"], $pimple);
    }

    private function loadCIGenerators($pimple)
    {
        $pimple['generator.controller'] = 'OutSource\Console\Generators\CIGenerator\ControllerGenerator';
        $pimple['generator.model'] = 'OutSource\Console\Generators\CIGenerator\ModelGenerator';
        $pimple['generator.library'] = 'OutSource\Console\Generators\CIGenerator\LibraryGenerator';
        $pimple['generator.view'] = 'OutSource\Console\Generators\CIGenerator\ViewGenerator';
        $pimple['generator.schema'] = 'OutSource\Console\Generators\CIGenerator\SchemaGenerator';
        $pimple['generator.category'] = 'OutSource\Console\Generators\CIGenerator\ModulesCategoryGenerator';
        $pimple['generator.adv'] = 'OutSource\Console\Generators\CIGenerator\ModulesAdvGenerator';
    }

    private function loadYafGenerators($pimple)
    {
        $pimple['generator.model'] = 'OutSource\Console\Generators\YafGenerator\ModelGenerator';
        $pimple['generator.library'] = 'OutSource\Console\Generators\YafGenerator\LibraryGenerator';
        $pimple['generator.modules'] = 'OutSource\Console\Generators\YafGenerator\ModulesGenerator';
        $pimple['generator.controller'] = 'OutSource\Console\Generators\YafGenerator\ControllerGenerator';
    }

    private function prepareCICommands()
    {
        $this->commands = [
            'OutSource\Console\Commands\CICommands\Scaffold\ScaffoldGeneratorCommand' ,
            'OutSource\Console\Commands\CICommands\Scaffold\ControllerGeneratorCommand' ,
            'OutSource\Console\Commands\CICommands\Publish\GeneratorPublishCommand' ,
            'OutSource\Console\Commands\CICommands\Publish\LayoutPublishCommand' ,
            'OutSource\Console\Commands\CICommands\Scaffold\ModelGeneratorCommand' ,
            'OutSource\Console\Commands\CICommands\Scaffold\LibraryGeneratorCommand' ,
            'OutSource\Console\Commands\CICommands\Scaffold\SchemaGeneratorCommand' ,
            'OutSource\Console\Commands\CICommands\Modules\CategoryModulesCommand' ,
            'OutSource\Console\Commands\CICommands\Modules\AdvModulesCommand'
        ];
    }

    private function prepareYafCommands()
    {
        $this->commands = [
            'OutSource\Console\Commands\YafCommands\Common\ModelCommand',
            'OutSource\Console\Commands\YafCommands\Common\LibraryCommand',
            'OutSource\Console\Commands\YafCommands\Common\ModulesCommand',
            'OutSource\Console\Commands\YafCommands\Common\ControllerCommand'
        ];
    }



}