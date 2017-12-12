<?php

namespace OutSource\Console\Generators\CIGenerator;

use OutSource\Console\Common\ModulesData;
use OutSource\Console\Generators\Contracts\GeneratorInterface;
use OutSource\Console\Utils\FileUtils;
use OutSource\FileSystem\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;

class ModulesAdvGenerator implements GeneratorInterface
{

    protected $commandConfig;
    protected $commandData;
    protected $files;

    public function __construct($commandConfig ,ModulesData $commandData , Filesystem $file)
    {
        $this->commandConfig = $commandConfig;
        $this->commandData = $commandData;
        $this->files = $file;
    }

    public function generate()
    {
        $this->generateController();
        $this->generateModel();
        $this->generateViews();
        $this->generatelib();
        $this->generateSql();
    }

    protected function generateSql()
    {
        $templateType = $this->commandConfig->get('applicationTemplates'); //系统路径

        $templateData = FileUtils::getTemplateModulesPath(
            $this->commandConfig->get('systemTemplates'),
            'adv'. DIRECTORY_SEPARATOR . 'adv.sql'
        );

        FileUtils::createFile(
            $templateType.'modules/',
            "adv.sql",
            $templateData
        );


        $this->commandData->commandComment("\n sql created: ".$templateType.'modules/');
    }

    protected function generateController()
    {
        $moduleName = ucfirst($this->commandData->modulesName);
        $fileName = $moduleName.'.php';
        $module = $this->commandConfig->get('modules');

        $templateData = FileUtils::getTemplateModulesPath(
            $this->commandConfig->get('systemTemplates'),
            'adv'. DIRECTORY_SEPARATOR . 'controller.stub'
        );

        $templateData = str_replace('$ROOTMODULES$', $module, $templateData);
        $templateData = str_replace('$MODULES$', $moduleName, $templateData);
        $templateData = str_replace('$MODULESNAME$', 'M_'.$moduleName, $templateData);
        $templateData = str_replace('$LIBNAME$', strtolower($moduleName.'lib'), $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('controller').$this->commandConfig->get('modules'),
            $fileName,
            $templateData
        );

        $this->commandData->commandComment("\n modules Controller created: ");
    }

    protected function generateModel()
    {
        $moduleName = ucfirst($this->commandData->modulesName);
        $fileName = 'M_' . $moduleName.'.php';

        $templateData = FileUtils::getTemplateModulesPath(
            $this->commandConfig->get('systemTemplates'),
            'adv'. DIRECTORY_SEPARATOR . 'model.stub'
        );

        $templateData = str_replace('$MODELNAME$', 'M_'.$moduleName, $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('model'),
            $fileName,
            $templateData
        );

        $this->commandData->commandComment("\n modules model created: ");
    }

    protected function generateViews()
    {
        $views = [
            'create.stub' => 'create.php' ,
            'edit.stub' => 'edit.php' ,
            'index.stub' => 'index.php'
        ];

        $moduleName = ucfirst($this->commandData->modulesName);
        $module = $this->commandConfig->get('modules');

        foreach ($views as $key => $view) {

            $viewPath = $this->commandConfig->get('views') . $this->commandConfig->get('modules') . $moduleName . DIRECTORY_SEPARATOR;

            $templateData = FileUtils::getTemplateModulesPath(
                $this->commandConfig->get('systemTemplates'),
                'adv'. DIRECTORY_SEPARATOR . $key
            );

            $templateData = str_replace('$MODULES$', $module, $templateData);
            $templateData = str_replace('$MODELNAME$', $moduleName, $templateData);

            FileUtils::createFile(
                $viewPath,
                $view,
                $templateData
            );
        }

        $this->commandData->commandComment("\n modules view created: ");

    }

    protected function generatelib()
    {
        $moduleName =  $this->commandData->modulesName;
        $fileName =   $moduleName.'lib'.'.php';

        $templateData = FileUtils::getTemplateModulesPath(
            $this->commandConfig->get('systemTemplates'),
            'adv'. DIRECTORY_SEPARATOR . 'lib.stub'
        );
        $module = $this->commandConfig->get('modules');

        $templateData = str_replace('$ROOTMODULES$', $module, $templateData);
        $templateData = str_replace('$MODEL$', 'M_'.ucfirst($moduleName), $templateData);
        $templateData = str_replace('$MODULESNAME$', ucfirst($moduleName).'lib', $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('library'),
            $fileName,
            $templateData
        );

        $this->commandData->commandComment("\n lib model created: ");
    }

    /**
     * 回滚函数
     */
    public function rollback()
    {
        $this->deleteController();
        $this->deleteModel();
        $this->deleteViews();
        $this->deletelib();
    }

    protected function deletelib()
    {
        $libName = ucfirst($this->commandData->modulesName).'lib';
        $libFileName = $libName.'.php';

        $controllerDeleteResult = FileUtils::deleteFile(
            $this->commandConfig->get('libraray').$this->commandConfig->get('modules'),
            $libFileName
        );

        $this->commandData->commandComment($libFileName." delete ");
    }

    protected function deleteController()
    {
        $controllerName = ucfirst($this->commandData->modulesName);
        $controllerFileName = $controllerName.'.php';

        $controllerDeleteResult = FileUtils::deleteFile(
            $this->commandConfig->get('controller').$this->commandConfig->get('modules'),
            $controllerFileName
        );

        $this->commandData->commandComment($controllerFileName." delete ");
    }

    protected function deleteModel()
    {
        $moduleName = ucfirst($this->commandData->modulesName);
        $moduleFileName = 'M_' . $moduleName.'.php';

        $controllerDeleteResult = FileUtils::deleteFile(
            $this->commandConfig->get('model'),
            $moduleFileName
        );

        $this->commandData->commandComment($moduleFileName." delete ");
    }

    protected function deleteViews()
    {
        $views = [
            'create.stub' => 'create.php' ,
            'edit.stub' => 'edit.php' ,
            'index.stub' => 'index.php'
        ];

        $moduleName = ucfirst($this->commandData->modulesName);

        foreach ($views as $key => $view) {

            $viewPath = $this->commandConfig->get('views') . $this->commandConfig->get('modules') . $moduleName . DIRECTORY_SEPARATOR;

            $controllerDeleteResult = FileUtils::deleteFile(
                $viewPath,
                $view
            );
            $this->commandData->commandComment($viewPath.$view." delete ");
        }
    }
}