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

class ModulesCommand extends BaseCommand
{

    protected $name = 'make:modules';

    protected $description = "Create a modules";

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
        $rollBack = $this->input->getOption('rollback');
        $generator = new $this->container['generator.modules']($this->config , $this->commandData , $this->container['files']);
        $generator->generateModules($rollBack);

        $command = $this->getApplication()->find('make:controller');
        $arguments = array(
            'command' => 'make:controller',
            'name'    =>  $input->getArgument('name') .'/index',
        );
        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }



    public function preConfig(InputInterface $input)
    {
        $modulesName = $input->getArgument('name');
        empty($modulesName) && $this->error('library name not empty');

        $this->config->set('modulesName' , $modulesName );

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['rollback', null, InputOption::VALUE_NONE, 'rollback modules'],
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