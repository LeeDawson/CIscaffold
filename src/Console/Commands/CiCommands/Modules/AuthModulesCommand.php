<?php

namespace OutSource\Console\Commands\CICommands\Modules;

use OutSource\Console\Common\CommandData;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use OutSource\Console\Common\ModulesData;
/**
 * 生成父类
 */
class AuthModulesCommand extends ModulesCommand
{

    protected $name = 'modules:auth';

    protected $description = "Create a Auth modules";

    protected $container;

    protected $commandData;

    public function __construct($pimple)
    {
        parent::__construct();
        $this->container = $pimple;
        $this->config = $pimple['config'];
        $this->modulesName = "Auth";
    }

    /**
     * 命令行的启动入口
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //处理公用的option
        $this->commandData = new ModulesData($this);

        $generator = new $this->container['generator.auth']($this->config , $this->commandData , $this->container['files']);
        if($this->option('rollback') ) {
            $generator->rollback();
        } else {
            $generator->generate();
        }

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['sqldump', null, InputOption::VALUE_REQUIRED, 'deafult dump sql '] ,
            ['rollback', null, InputOption::VALUE_NONE, 'delete scaffold'],
        ];
    }


}