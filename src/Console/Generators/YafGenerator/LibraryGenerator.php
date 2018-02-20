<?php

namespace OutSource\Console\Generators\YafGenerator;

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
        $FileName = ucfirst($modelName).'lib.php';
        $preModelName = 'M_'.ucfirst($modelName);

        $templateData = FileUtils::getTemplateScaffoldPath($this->commandConfig->get('systemTemplates'), 'scaffold_libraries.stub');
//        $templateData = str_replace('$LIBNAME$', ucfirst($modelName).'Lib', $templateData);
//        $templateData = str_replace('$MODEL_NAME$', $preModelName, $templateData);
//        $templateData = str_replace('$PRIMARY$', $this->commandData->getModelPrimaryKey(), $templateData);
//        $templateData = str_replace('$SOFTDELETEWHERE$', $this->getSoftDelete($this->commandConfig->softDelete), $templateData);
//        $templateData = str_replace('$SOFTDELETE$', $this->commandConfig->softDelete, $templateData);
//        $templateData = str_replace('$TIMESTAMPE$', $this->getTimeStamp($this->commandConfig->timeStamp), $templateData);

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
        $libraryName = $this->commandConfig->libraryName;
        $FileName = ucfirst($this->commandConfig->libraryFileName).'.php';
        $templateData = FileUtils::getTemplateScaffoldPath($this->commandConfig->get('systemTemplates'), 'library.stub');
        $templateData = str_replace('$LIBRARY_NAME$', ucfirst($libraryName), $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('library') . $this->commandConfig->get('libraryPath'),
            $FileName,
            $templateData
        );

        $this->commandData->commandComment("\nlibrary created: ");
        $this->commandData->commandInfo($FileName);
    }



    /**
     * 回滚函数
     */
    public function rollback()
    {
        $libraryName = $this->commandData->modelName;
        $fileName = ucfirst($libraryName).'lib.php';
        $result = FileUtils::deleteFile(
            $this->commandConfig->get('library'),
            $fileName
        );
        $this->commandData->commandComment($fileName." delete ");
    }
}