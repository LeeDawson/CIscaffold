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

class LibraryGeneratorCommand extends BaseCommand
{
    protected $name = 'make:library';

    protected $description = "Create a library";

    protected $container;




    public function __construct($pimple)
    {
        parent::__construct();

        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD );
        $this->container = $pimple;
        $this->config = $pimple['config'];
    }

    /**
     * 命令行的启动入口
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $libraryName = $input->getArgument('name');

        if(empty($libraryName))
            $this->error('library name not empty');

        $this->commandData->modelName = $libraryName;
        $generator = new $this->container['generator.library']($this->config,$this->commandData,$this->container['files']);
        $generator->generateLibrary();
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

        $this->config->set('primaryName',$primaryName);
        $this->config->set('tableName',$tableName);

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