<?php

namespace OutSource\Console\Commands\YafCommands\Common;

use OutSource\Console\Commands\BaseCommand;
use OutSource\Console\Common\CommandData;
use OutSource\Console\Common\GeneratorConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;

class ControllerCommand extends BaseCommand
{

    protected $name = 'make:controller';

    protected $description = "Create a controller";

    protected $container;

    public function __construct($pimple)
    {
        parent::__construct();

        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD);
        $this->container = $pimple;
        $this->config = $pimple['config'];
    }

    /**
     * 命令行的启动入口
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->preConfig($input);
        $generator = new $this->container['generator.controller']($this->config , $this->commandData , $this->container['files']);
        $generator->generateController();
    }

    public function preConfig(InputInterface $input)
    {
        $controllerName = $input->getArgument('name');
        empty($controllerName) && $this->error('controller name not empty');

        $this->checkArguments($controllerName);

        $this->config->set('controllerName' , $this->getControllerName($controllerName) );
        $this->config->set('controllerFileName' , $this->getControllerFileName($controllerName) );
        $this->config->set('controllerPath' , $this->getControllerPath($controllerName) );

//        $this->config->set('libraryName' , $this->getLibraryName($libraryName) );
//        $this->config->set('libraryFileName' , $this->getLibraryFileName($libraryName) );
//        $this->config->set('libraryPath' , $this->getLibraryPath($libraryName) );

    }

    private function checkArguments($controllerName)
    {
         $info = explode('/' , $controllerName);

         if(count($info) > 2) {
             $this->error('Arguments too many, must "modules/controller" format   or "controller name"');
             exit();
         }
    }

    private function getControllerFileName($libraryName)
    {
        $names = explode('/' , $libraryName);
        return ucfirst(array_pop($names));
    }

    private function getControllerName($controllerName)
    {
        $names = explode('/' , $controllerName);
        return ucfirst(array_pop($names)).'Controller';
    }

    private function getControllerPath($libraryName)
    {
        $names = explode('/' , $libraryName);

        if(count($names) == 1)
            return "";

        array_pop($names);

        return implode('/' , array_map('ucfirst' , $names)) .'/Controllers/';
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [

        ];
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'library name'],
        ];
    }
}