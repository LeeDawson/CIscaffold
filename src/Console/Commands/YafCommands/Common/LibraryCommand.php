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

class LibraryCommand extends BaseCommand
{

    protected $name = 'make:library';

    protected $description = "Create a library";

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
        $generator = new $this->container['generator.library']($this->config , $this->commandData , $this->container['files']);
        $generator->generateLibrary();
    }

    public function preConfig(InputInterface $input)
    {
        $libraryName = $input->getArgument('name');
        empty($libraryName) && $this->error('library name not empty');

        $this->config->set('libraryName' , $this->getLibraryName($libraryName) );
        $this->config->set('libraryFileName' , $this->getLibraryFileName($libraryName) );
        $this->config->set('libraryPath' , $this->getLibraryPath($libraryName) );
    }

    private function getLibraryFileName($libraryName) {

        $names = explode('/' , $libraryName);
        return array_pop($names);
    }

    private function getLibraryName($libraryName)
    {
        $names = explode('/' , $libraryName);
        return implode('_' , array_map('ucfirst' , $names));
    }

    private function getLibraryPath($libraryName)
    {
        $names = explode('/' , $libraryName);

        if(count($names) == 1)
            return "";

        array_pop($names);

        return implode('/' , array_map('ucfirst' , $names)) .'/';
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