<?php

namespace OutSource\Console\Generators\CIGenerator;

use OutSource\Console\Common\CommandData;
use OutSource\Console\Generators\Contracts\GeneratorInterface;
use OutSource\Console\Utils\FileUtils;
use OutSource\FileSystem\FileSystem;

class SchemaGenerator implements GeneratorInterface
{

    protected $commandConfig;
    protected $commandData;

    protected $files;
    protected $Urls;
    protected $viewPath;

    public function __construct($commandConfig, CommandData $commandData, Filesystem $file)
    {
        $this->commandConfig = $commandConfig;
        $this->commandData = $commandData;
        $this->files = $file;
    }

    public function generate()
    {
        $schemaName = $this->commandData->modelName;
        $fileName = ucfirst($schemaName).'.php';
        $templateData = FileUtils::getTemplateByPath($this->commandConfig->get('systemTemplates') . DIRECTORY_SEPARATOR . 'Schema' . DIRECTORY_SEPARATOR . 'schema.stub');
        $templateData = str_replace('$SCHEMA$', ucfirst($schemaName), $templateData);

        // TODO: Implement generate() method.
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }
}