<?php

namespace OutSource\Console\Commands\CICommands\Scaffold;

use OutSource\Console\Commands\BaseCommand;
use OutSource\Console\Common\CommandData;
use OutSource\Console\Common\GeneratorConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;

class ControllerGeneratorCommand extends BaseCommand
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
        $controller = $input->getArgument('name');

        if(empty($controller)) {
            $this->error('controller name not empty');
        }

        $this->commandData->modelName = $controller;
        $generator = new $this->container['generator.controller']($this->config , $this->commandData , $this->container['files']);
        $generator->generateController();

    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
              ['scaffold', null, InputOption::VALUE_REQUIRED, 'generate scaffold controller'],
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
            ['name', InputArgument::REQUIRED, 'Controller name'],
        ];
    }
}