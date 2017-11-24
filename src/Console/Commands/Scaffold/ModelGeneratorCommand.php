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

class ModelGeneratorCommand extends BaseCommand
{
    protected $name = 'make:model';

    protected $description = "Create a model";

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
        $model = $input->getArgument('name');

        if(empty($model)) {
            $this->error('model name not empty');
        }

        $this->commandData->modelName = $model;

        $this->preConfig($input, $this->config);

        $generator = new $this->container['generator.model']($this->config , $this->commandData , $this->container['files']);
        $generator->generate();
    }

    public function preConfig(InputInterface $input,$config)
    {
        $primaryName = 'id';
        $tableName = $input->getArgument('name');

        if ($input->getOption('primary')) {
            $primaryName = $input->getOption('primary');
        }

        if ($input->getOption('tableName')) {
            $this->tableName = $input->getOption('tableName');
        }

        $this->config->set('primaryName', $primaryName);
        $this->config->set('tableName', $tableName);

    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['tableName', null, InputOption::VALUE_REQUIRED, 'Table Name'],
            ['primary', null, InputOption::VALUE_REQUIRED, 'Custom primary key']
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
            ['name', InputArgument::REQUIRED, 'model name'],
        ];
    }
}