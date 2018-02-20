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

class ModelCommand extends BaseCommand
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
        $this->preConfig($input);
        $generator = new $this->container['generator.model']($this->config , $this->commandData , $this->container['files']);
        $generator->generateModel();
    }

    /**
     * 准备生成model的参数
     *
     * @param InputInterface  $input
     */
    public function preConfig(InputInterface $input )
    {
        $model = $input->getArgument('name');
        empty($model) && $this->error('model name not empty');

        $primaryName = $input->getOption('primary') ?? 'id';
        $tableName = $input->getOption('tableName') ??  $input->getArgument('name');
        $this->config->set('primaryName', $primaryName);
        $this->config->set('tableName', $tableName);
        $this->config->set('modelName' , $model);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['tableName', null, InputOption::VALUE_REQUIRED, 'set Table Name'],
            ['primary', null, InputOption::VALUE_REQUIRED, 'set Custom primary key']
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
            ['name', InputArgument::REQUIRED, 'set model name'],
        ];
    }
}