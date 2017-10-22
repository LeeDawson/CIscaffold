<?php

namespace OutSource\Console\Generators\CIGenerator;

use OutSource\Console\Common\CommandData;
use OutSource\Console\Generators\Contracts\GeneratorInterface;
use OutSource\Console\Utils\FileUtils;
use OutSource\FileSystem\FileSystem;
use Symfony\Component\Console\Exception\LogicException;

class ControllerGenerator implements GeneratorInterface
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
        $controllerName = $this->commandData->modelName;
        $fileName = ucfirst($controllerName).'.php';

        $replaceUrls = [
            '$EDITVIEW$' => ucfirst($controllerName).DIRECTORY_SEPARATOR.'edit',
            '$INDEXURL$' => $this->commandConfig->get('modules').$controllerName,
            '$CREATEVIEW$' => ucfirst($controllerName).DIRECTORY_SEPARATOR.'create',
            '$INDEXVIEW$' => ucfirst($controllerName).DIRECTORY_SEPARATOR.'index',
        ];
        $templateData = FileUtils::getTemplateScaffoldPath($this->commandConfig->get('systemTemplates'),'scaffold_controller.stub');
        $templateData = str_replace('$CONTROLER_NAME$', ucfirst($controllerName), $templateData);
        $templateData = str_replace('$LIBRARY_NAME$', strtolower($controllerName).'lib', $templateData);
        $templateData = str_replace('$PRIMARY$', $this->commandData->getModelPrimaryKey(), $templateData);
        $templateData = str_replace('$SOFTDELETE$', $this->commandConfig->softDelete ? "destory" : "delete"   , $templateData);
        $templateData = str_replace('$SHOWSOFTDELETE$', $this->commandConfig->softDelete ? '$this->_data["'.$this->commandConfig->softDelete.'"] = 1;' : " "   , $templateData);
        $templateData = str_replace('$SAVESOFTDELETE$', $this->commandConfig->softDelete ? '$postData["'.$this->commandConfig->softDelete.'"] = 1;' : " "   , $templateData);
        $templateData = str_replace('$SOFTDELETE$', $this->commandConfig->softDelete ? "destory" : "delete"   , $templateData);

        foreach ($replaceUrls as $Key => $replaceUrl) {
            $templateData = str_replace($Key, $replaceUrl, $templateData);
        }

        FileUtils::createFile(
            $this->commandConfig->get('controller').$this->commandConfig->get('modules'),
            ucfirst($fileName),
            $templateData
        );
        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($controllerName);
    }

    /**
     * 生成函数
     *
     */
    public function generateController()
    {
        $controllerName = $this->commandData->modelName;
        $fileName = $controllerName.'.php';
        $templateData = FileUtils::getTemplateScaffoldPath($this->commandConfig->get('systemTemplates'),'controller.stub');
        $templateData = str_replace('$MODEL_NAME$', $controllerName, $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('controller').$this->commandConfig->get('modules'),
            $fileName,
            $templateData
        );

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($controllerName);
        // TODO: Implement generate() method.
    }

    /**
     * 回滚函数
     *
     */
    public function rollback()
    {
        $controllerName = $this->commandData->modelName;


        $fileName = ucfirst($controllerName).'.php';

        $result = FileUtils::deleteFile(
            $this->commandConfig->get('controller').$this->commandConfig->get('modules'),
            ucfirst($fileName)
        );

        $this->commandData->commandComment($fileName." delete ");
    }
}