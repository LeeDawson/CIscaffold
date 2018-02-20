<?php

namespace OutSource\Console\Generators\YafGenerator;

use OutSource\Console\Common\CommandData;
use OutSource\Console\Generators\Contracts\GeneratorInterface;
use OutSource\Console\Utils\FileUtils;
use OutSource\FileSystem\FileSystem;

class ModulesGenerator implements GeneratorInterface
{

    protected $commandConfig;
    protected $commandData;

    protected $files;

    public function __construct($commandConfig,CommandData $commandData,Filesystem $file)
    {
        $this->commandConfig = $commandConfig;
        $this->commandData = $commandData;
        $this->files = $file;
    }

    /**
     * 生成函数
     */
    public function generateModules($rollBack)
    {
        if($rollBack) {
            $this->rollback();
            return true;
        }
        $modulesName = ucfirst($this->commandConfig->modulesName);
        $modulesPath = $this->commandConfig->get('modules'). $modulesName;

        if( $this->files->exists($modulesPath) ) {
            $this->commandData->commandComment("\n Modules exist ");
            exit();
        }

        $this->files->createDirectoryIfNotExist($modulesPath );
        $this->files->createDirectoryIfNotExist($modulesPath . DIRECTORY_SEPARATOR. 'controllers');
        $this->files->createDirectoryIfNotExist($modulesPath . DIRECTORY_SEPARATOR. 'views' );

        $this->commandData->commandComment("\nModules created complete: ");
        $this->loadInI($modulesName);




    }

    public function loadInI($modulesName)
    {
        $configPath = $this->commandConfig->get('config').DIRECTORY_SEPARATOR.'application.ini';

        $this->files->exists($configPath) &&  $applicationConf = file_get_contents($configPath);

        if(empty($applicationConf)) {
            $this->commandData->commandError('not found applicaion_ini');
            exit();
        }

        $content = preg_replace("/.*?application.modules.*?'(.*?)'/" , "application.modules = '$1,$modulesName'", $applicationConf );
        file_put_contents($configPath , $content);

        $this->commandData->commandComment("\nModules application_ini append: ");
    }



    public function rollback()
    {
        $modulesName = ucfirst($this->commandConfig->modulesName);
        $configPath = $this->commandConfig->get('config').DIRECTORY_SEPARATOR.'application.ini';
        $applicationConf = file_get_contents($configPath);

        $content = preg_replace( "/,$modulesName/" , '', $applicationConf  );
        file_put_contents($configPath , $content);

        $this->files->deleteDirectory($this->commandConfig->get('modules'). $modulesName);
        $this->commandData->commandComment($modulesName." delete ");
        $this->commandData->commandComment($modulesName."application_ini delete ");
        exit();
    }

    /**
     * 生成函数
     */
    public function generate()
    {
        // TODO: Implement generate() method.
    }
}