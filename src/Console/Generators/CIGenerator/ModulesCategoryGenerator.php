<?php

namespace OutSource\Console\Generators\CIGenerator;

use OutSource\Console\Common\ModulesData;
use OutSource\Console\Generators\Contracts\GeneratorInterface;
use OutSource\Console\Utils\FileUtils;
use OutSource\FileSystem\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;

class ModulesCategoryGenerator implements GeneratorInterface
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
        $this->generateVies();


        //todo 4. 导出数据库
        //todo 5. 看看是不是需要生成数据库的;
    }

    protected function generateController()
    {
        $moduleName = ucfirst($this->commandData->modulesName);
        $fileName = $moduleName.'.php';

        $templateData = FileUtils::getTemplateModulesPath(
            $this->commandConfig->get('systemTemplates') ,
            'category'. DIRECTORY_SEPARATOR . 'controller.stub'
        );

        $templateData = str_replace('$MODULES$', $moduleName, $templateData);
        $templateData = str_replace('$MODULESNAME$', 'M_'.$moduleName, $templateData);

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
            $this->commandConfig->get('systemTemplates') ,
            'category'. DIRECTORY_SEPARATOR . 'model.stub'
        );

        $templateData = str_replace('$MODEL$', 'M_'.$moduleName, $templateData);

        FileUtils::createFile(
            $this->commandConfig->get('model') ,
            $fileName,
            $templateData
        );

        $this->commandData->commandComment("\n modules model created: ");
    }

    protected function generateVies()
    {
        $views = [
            'manage.stub' => 'manage.php' ,
            'category_save.stub' => 'category_save.php'
        ];

        $moduleName = ucfirst($this->commandData->modulesName);
        $module = $this->commandConfig->get('modules');

        foreach ($views as $key => $view) {

            $viewPath = $this->commandConfig->get('views') . $this->commandConfig->get('modules') . $moduleName . DIRECTORY_SEPARATOR;

            $templateData = FileUtils::getTemplateModulesPath(
                $this->commandConfig->get('systemTemplates') ,
                'category'. DIRECTORY_SEPARATOR . $key
            );

            $templateData = str_replace('$MODULES$', $module , $templateData);
            $templateData = str_replace('$MODELNAME$', $moduleName, $templateData);


            FileUtils::createFile(
                $viewPath,
                $view,
                $templateData
            );
        }

        $this->commandData->commandComment("\n modules view created: ");

    }

    /**
     * 回滚函数
     *
     */
    public function rollback()
    {
        // TODO: Implement rollback() method.
    }
}