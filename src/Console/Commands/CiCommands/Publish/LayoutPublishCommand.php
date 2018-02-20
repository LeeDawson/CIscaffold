<?php

namespace OutSource\Console\Commands\CICommands\Publish;

use OutSource\Console\Commands\BaseCommand;
use OutSource\FileSystem\FileSystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LayoutPublishCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'publish:layout';

    /**
     * @var OutSource\FileSystem\FileSystem
     */
    protected $files;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes all template files';


    public function __construct($pimple)
    {
        parent::__construct();
        $this->config = $pimple['config'];
        $this->files = $pimple['files'];
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->copyResource();
        $this->copyView();
        $this->copyFields();
    }

    private function copyResource()
    {

        $viewsPath = $this->config['applicationTemplates']; //项目路径
        $templateType = $this->config['systemTemplates']; //系统路径
        $modules = $this->config['modules'].DIRECTORY_SEPARATOR;
        $this->createDirectories($viewsPath);
        
        foreach ($this->getResource() as $name => $addr) {
            $dirname = dirname($viewsPath).DIRECTORY_SEPARATOR.$modules.$addr;
            $this->files->copyDirectory($templateType.$name, $dirname);
            $this->comment($name.' published');
            $this->info(dirname($viewsPath).$addr);
        }

        foreach ($this->getLayout() as $name => $addr) {
            $this->files->copy($templateType.$name.'.stub', $viewsPath.$addr);
            $this->comment($addr.' published');
            $this->info($viewsPath);
        }

        foreach ($this->getVendor() as $name => $addr) {
            $dirname = dirname($viewsPath) . DIRECTORY_SEPARATOR . $addr;
            $this->files->copyDirectory($templateType.$name, $dirname);
            $this->comment($name.' published');
            $this->info(dirname($viewsPath).$addr);
        }

        $this->comment('published finish');

    }

    private function copyView()
    {
        $viewsPath = $this->config['applicationTemplates']; //项目路径
        $templateType = $this->config['systemTemplates']; //系统路径
        $this->createDirectories($viewsPath);
        $views = $this->getView();
        foreach ($views as $name => $addr) {
            $this->files->copy($templateType.$name.'.stub', $viewsPath.$addr);
            $this->comment($addr.' published');
            $this->info($viewsPath);
        }
    }

    private function copyFields()
    {
        $viewsPath = $this->config['applicationTemplates']; //项目路径
        $templateType = $this->config['systemTemplates']; //系统路径
        $this->createDirectories($viewsPath);
        $fields = $this->getFields();
        foreach ($fields as $name => $addr) {
            $this->files->copy($templateType.$name.'.stub', $viewsPath.$addr);
            $this->comment($addr.' published');
            $this->info($viewsPath);
        }
    }

    private function createDirectories($viewsPath)
    {
        $this->files->createDirectoryIfNotExist($viewsPath, false);
        $this->files->createDirectoryIfNotExist($viewsPath.'Layouts', false);
        $this->files->createDirectoryIfNotExist($viewsPath.'View', false);
        $this->files->createDirectoryIfNotExist($viewsPath.'Fields', false);
    }

    private function getLayout()
    {
        return [
            'layouts/common_header'     => 'Layouts/common_header.stub',
            'layouts/common_footer'     => 'Layouts/common_footer.stub',
            'layouts/common_menu'       => 'Layouts/common_menu.stub',
            'layouts/pagination'        => 'Layouts/pagination.stub',
        ];
    }

    private function getFields()
    {
        return [
            'fields/date'                   => 'Fields/date.stub',
            'fields/date_js'                => 'Fields/date_js.stub',
            'fields/date_resource'          => 'Fields/date_resource.stub',
            'fields/file'                   => 'Fields/file.stub',
            'fields/file_edit'              => 'Fields/file_edit.stub',
            'fields/file_one'               => 'Fields/file_one.stub',
            'fields/file_one_js'            => 'Fields/file_one_js.stub',
            'fields/file_one_resource'      => 'Fields/file_one_resource.stub',
            'fields/file_resource'          => 'Fields/file_resource.stub',
            'fields/radio'                  => 'Fields/radio.stub',
            'fields/radio_group'            => 'Fields/radio_group.stub',
            'fields/select'                 => 'Fields/select.stub',
            'fields/select_group'           => 'Fields/select_group.stub',
            'fields/text'                   => 'Fields/text.stub',
            'fields/textarea'               => 'Fields/textarea.stub',
        ];
    }

    private function getView()
    {
        return [
              'view/index'  => 'View/index.stub',
              'view/edit'   => 'View/edit.stub',
              'view/create' => 'View/create.stub',
        ];
    }

    private function getResource()
    {
        return [
            'public/admin/css'          => '/css',
            'public/admin/fonts'        => '/fonts',
            'public/admin/images'       => '/images',
            'public/admin/js'           => '/js'
        ];
    }

    private function getVendor()
    {
        return [
            'public/vendor'             => '/vendor'
        ];
    }




    /**
     * Replaces dynamic variables of template.
     *
     * @param string $templateData
     *
     * @return string
     */
    private function fillTemplate($templateData)
    {
        $templateData = str_replace(
            '$NAMESPACE_CONTROLLER$',
            config('infyom.laravel_generator.namespace.controller'), $templateData
        );

        $templateData = str_replace(
            '$NAMESPACE_REQUEST$',
            config('infyom.laravel_generator.namespace.request'), $templateData
        );

        return $templateData;
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
