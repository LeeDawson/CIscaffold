<?php

namespace OutSource\Console\Commands\Scaffold;

use OutSource\Console\Commands\BaseCommand;
use OutSource\Console\Common\CommandData;
use OutSource\Console\Common\GeneratorConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;

class SchemaGeneratorCommand extends BaseCommand
{
    protected $name = 'make:schema';

    protected $description = "Create a schema";

    protected $container;

    public function __construct($pimple)
    {
        parent::__construct();
        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD);
        $this->container = $pimple;
        $this->config = new GeneratorConfig($this->commandData, $pimple['config']);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //使用config获取到用户输入的option
        $this->preOptions();

        $schemaName = $input->getArgument('name');

        if(empty($schemaName)) {
            $this->error('controller name not empty');
        }

        $this->commandData->modelName = $schemaName;

        $generator = new $this->container['generator.schema']($this->config , $this->commandData , $this->container['files']);
        $generator->generate();

    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['primary', null, InputOption::VALUE_REQUIRED, 'Custom primary key'],
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