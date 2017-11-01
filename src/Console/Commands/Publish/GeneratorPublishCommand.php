<?php

namespace OutSource\Console\Commands\Publish;

use OutSource\Console\Commands\Publish\PublishBaseCommand;
use OutSource\Console\Utils\FileUtils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 生成父类
 *
 */
class GeneratorPublishCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'publish:init';

    public function __construct($pimple)
    {
        parent::__construct();
        $this->container = $pimple;
        $this->config = $pimple['config'];
        $this->files = $pimple['files'];
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes & base controller, base test cases traits.';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initControllerCoreBase(); //初始化my_controller
        $this->initModelCoreBase();     //初始化my_model
        $this->initServerBase();        //初始化my_server
        $this->initControllerBase();    //初始化父类控制器
        $this->initPagination();        //初始化分页样式
        $this->initValidation();        //初始化验证类
        $this->initUploadify();         //初始化文件类

        $this->Comment("\n init completed: ");
    }

    /**
     * 初始化验证代码
     *
     */
    private function initValidation()
    {
        $validation = FileUtils::getTemplateByPath($this->config->get('systemTemplates').'Validation'.DIRECTORY_SEPARATOR."Validation.php");
        FileUtils::createFile(
            $this->config->get('library').'/Validation/',
            "Validation.php",
            $validation
        );

        $validation = FileUtils::getTemplateByPath($this->config->get('systemTemplates').'Validation'.DIRECTORY_SEPARATOR."Rule.php");
        FileUtils::createFile(
            $this->config->get('library').'/Validation/',
            "Rule.php",
            $validation
        );

        $this->Info("Validation.php");
        $this->Info("Rule.php");
    }

    /**
     * 初始化文件类
     *
     */
    private function initUploadify()
    {
        $UploadHandler = FileUtils::getTemplateByPath($this->config->get('systemTemplates').'Uploadify'.DIRECTORY_SEPARATOR."UploadHandler.php");
        FileUtils::createFile(
            $this->config->get('library'),
            "UploadHandler.php",
            $UploadHandler
        );

        $this->Info("UploadHandler.php");

    }

    /**
     * 初始化基础的控制器
     *
     */
    private function initControllerBase()
    {
        $templateData = FileUtils::getTemplateScaffoldPath($this->config->get('systemTemplates'),'adminBase.stub');
        $fileName = ucfirst(rtrim($this->config->get('modules'),DIRECTORY_SEPARATOR))."Base.php";
        $modulesHeader = $this->config->get('modules').'common_header';
        $modules = $this->config->get('modules').'';
        $modulesFooter = $this->config->get('modules').'common_footer';

        $templateData = str_replace('$viewModulesHeader$',$modulesHeader,$templateData);
        $templateData = str_replace('$viewModules$', $modules, $templateData);
        $templateData = str_replace('$viewModulesFooter$',$modulesFooter,$templateData);

        FileUtils::createFile(
            $this->config->get('controller').$this->config->get('modules'),
            $fileName,
            $templateData
        );
        $this->Info($fileName);
    }

    /**
     * 初始化服务层的基础控制器
     *
     */
    private function initServerBase()
    {
        $templateData = FileUtils::getTemplateScaffoldPath($this->config->get('systemTemplates'),'myServer.stub');
        $fileName = "MY_Server.php";

        FileUtils::createFile(
            $this->config->get('core'),
            $fileName,
            $templateData
        );
        $this->Info($fileName);
    }

    /**
     * 初始化model层
     *
     */
    private function initModelCoreBase()
    {
        $templateData = FileUtils::getTemplateScaffoldPath($this->config->get('systemTemplates'),'myModel.stub');
        $fileName = "MY_Model.php";

        FileUtils::createFile(
            $this->config->get('core'),
            $fileName,
            $templateData
        );
        $this->Info($fileName);
    }

    /**
     * 初始化控制器核心
     *
     */
    private function initControllerCoreBase()
    {
        $templateData = FileUtils::getTemplateScaffoldPath($this->config->get('systemTemplates'),'myController.stub');
        $fileName = "MY_Controller.php";

        FileUtils::createFile(
            $this->config->get('core'),
            $fileName,
            $templateData
        );
        $this->Info($fileName);
    }

    /**
     * 初始化分页的配置
     *
     */
    private function initPagination()
    {
        $name = "pagination.stub";
        $templateData = FileUtils::fileExistWithFetch($this->config, $name );

        FileUtils::createFile(
            $this->config->get('config'),
            "pagination.php",
            $templateData
        );
        $this->Info("pagination.php");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [];
    }



    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
