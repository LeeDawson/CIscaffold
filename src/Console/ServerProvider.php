<?php

namespace OutSource\Console;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use OutSource\Console\Application;
use OutSource\Console\Commands\Scaffold\ScaffoldGeneratorCommand;
use OutSource\Console\Generators\CIGenerator\ControllerGenerator;
use OutSource\Console\Commands\Scaffold\ControllerGeneratorCommand;
use OutSource\Console\Commands\Publish\GeneratorPublishCommand;
use OutSource\Console\Commands\Publish\LayoutPublishCommand;
use OutSource\Console\Generators\CIGenerator\ModelGenerator;
use OutSource\Console\Commands\Scaffold\LibraryGeneratorCommand;
use OutSource\Console\Commands\Scaffold\ModelGeneratorCommand;
use OutSource\Console\Generators\CIGenerator\LibraryGenerator;
use OutSource\Console\Generators\CIGenerator\ViewGenerator;

class ServerProvider implements ServiceProviderInterface
{
    /**
     * contain
     *
     * @var object
     */
    protected static $container = null;


    protected $commands = [
        ScaffoldGeneratorCommand::class,
        ControllerGeneratorCommand::class,
        GeneratorPublishCommand::class,
        LayoutPublishCommand::class,
        ModelGeneratorCommand::class,
        LibraryGeneratorCommand::class
    ];


    function register(Container $pimple)
    {
        $this->initGenerator($pimple);
        return $pimple['console'] = function() use($pimple){
           return  $this->initKernlConsole($pimple);
        };


    }

    public function initKernlConsole($pimple)
    {
        $this->addCommands($this->commands , $pimple);
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
        if(! self::$container instanceof Application)
            self::$container = new Application();

        return self::$container;
    }

    protected function initGenerator($pimple)
    {
        $dirver = $pimple['config']['driver'];
        call_user_func([$this,"load".$dirver."Generators"],$pimple);
    }

    private function loadCIGenerators($pimple)
    {
        $pimple['generator.controller'] = ControllerGenerator::class;
        $pimple['generator.model'] = ModelGenerator::class;
        $pimple['generator.library'] = LibraryGenerator::class;
        $pimple['generator.view'] = ViewGenerator::class;
    }



}