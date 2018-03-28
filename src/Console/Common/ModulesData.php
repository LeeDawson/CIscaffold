<?php

namespace OutSource\Console\Common;

use  OutSource\Console\Commands\CICommands\Modules\ModulesCommand;

class ModulesData
{

    public $modulesName;

    public $sqlDump = true;

    public $options;

    public $commandObj;

    public function __construct(ModulesCommand $commands)
    {
        $this->commandObj = $commands;

        $this->modulesName = $commands->modulesName;
        
        foreach ($commands->options() as $key => $option) {
            $this->setOption($key, $option);
        }

        $this->handleOption();
    }

    public function handleOption()
    {
        if($this->getOption('moduleName')) {
            $this->modulesName = $this->getOption('moduleName');
        }

        if($this->getOption('sqldump')) {
            $this->sqlDump = false;
        }
    }

    public function getOption($key)
    {
        return  isset($this->options[$key]) ? $this->options[$key] : null;
    }

    public function setOption($key , $value)
    {
        $this->options[$key] = $value;
    }


    public function commandInfo($message)
    {
        $this->commandObj->info($message);
    }

    public function commandError($error)
    {
        $this->commandObj->error($error);
    }

    public function commandComment($message)
    {

        $this->commandObj->comment($message);
    }

    public function commandWarn($warning)
    {
        $this->commandObj->warn($warning);
    }


}