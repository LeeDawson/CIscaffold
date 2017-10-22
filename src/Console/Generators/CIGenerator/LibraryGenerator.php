<?php

namespace OutSource\Console\Generators\CIGenerator;

use OutSource\Console\Common\CommandData;
use OutSource\Console\Generators\Contracts\GeneratorInterface;
use OutSource\Console\Utils\FileUtils;
use OutSource\FileSystem\FileSystem;

class LibraryGenerator implements GeneratorInterface
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

    public function generate()
    {
        $modelName = $this->commandData->modelName;
        $FileName = ucfirst($modelName).'Lib.php';
        $preModelName = 'M_'.$modelName;
        $modelIncludeName = $this->commandConfig->get('modules').$preModelName;

        $templateData = FileUtils::getTemplateScaffoldPath($this->commandConfig->get('systemTemplates'),'scaffold_libraries.stub');
        $templateData = str_replace('$LIBNAME$', ucfirst($modelName).'Lib', $templateData);
        $templateData = str_replace('$MODEL_INCULDE_NAME$', $modelIncludeName, $templateData);
        $templateData = str_replace('$MODEL_NAME$', $preModelName, $templateData);
        $templateData = str_replace('$PRIMARY$', $this->commandData->getModelPrimaryKey(), $templateData);
        $templateData = str_replace('$SOFTDELETE$', $this->commandConfig->softDelete, $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('library'),
            $FileName,
            $templateData
        );

        $this->commandData->commandComment("\nlibrary created: ");
        $this->commandData->commandInfo($FileName);
    }

    public function generateLibrary()
    {
        $libraryName = $this->commandData->modelName;
        $fileName = $libraryName.'.php';
        $templateData = FileUtils::getTemplateScaffoldPath($this->commandConfig->get('systemTemplates'),'libraries.stub');
        $templateData = str_replace('$LIBNAME$', $libraryName, $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('library'),
            $fileName,
            $templateData
        );

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($libraryName);
    }



    /**
     * 回滚函数
     *
     */
    public function rollback()
    {
        $libraryName = $this->commandData->modelName;
        $fileName = ucfirst($libraryName).'Lib.php';
        $result = FileUtils::deleteFile(
            $this->commandConfig->get('library'),
            $fileName
        );
        $this->commandData->commandComment($fileName." delete ");
    }
}