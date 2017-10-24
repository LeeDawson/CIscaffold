<?php

namespace OutSource\Console\Commands\Scaffold;

use OutSource\Console\Commands\BaseCommand;
use OutSource\Console\Common\CommandData;
use OutSource\Console\Common\GeneratorConfig;
use OutSource\Console\Generators\CIGenerator\ControllerGenerator;
use OutSource\Console\Generators\CIGenerator\ModelGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Tests\Output\NullOutputTest;

class ScaffoldGeneratorCommand extends BaseCommand
{
    protected $name = 'make:scaffold';

    protected $description = "Create a full CRUD views for given model";

    public function __construct($pimple)
    {
        parent::__construct();
        $this->container = $pimple;
        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD );
        $this->config = new GeneratorConfig($this->commandData , $pimple['config']);
    }

    /**
     * 命令行的启动入口
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->commandData->modelName = $this->input->getArgument('model');

        if( $this->option('rollback') ){
            $this->rollbackScaffold();
        } elseif( $this->option('schema') ){
            $this->generScaffoldBySchema();
        } else {
            $this->generScaffold();
        }
    }

    protected function generScaffoldBySchema()
    {

    }

    protected function generScaffold()
    {
        parent::handle();
        $this->generateCommonItems();
        $this->generateScaffoldItems();
    }

    protected function rollbackScaffold()
    {
        $this->rollbackCommonItems();
        $this->rollbackScaffoldItems();
    }


    private function generateCommonItems()
    {
        if (!$this->isSkip('model')) {
            $generator = new $this->container['generator.model']($this->config,$this->commandData,$this->container['files']);
            $generator->generate();
        }

        if (!$this->isSkip('library')) {
            $generator = new $this->container['generator.library']($this->config,$this->commandData,$this->container['files']);
            $generator->generate();
        }
    }
    private function generateScaffoldItems()
    {
        if (!$this->isSkip('controllers') ) {
            $generator = new $this->container['generator.controller']($this->config,$this->commandData,$this->container['files']);
            $generator->generate();
        }

        if (!$this->isSkip('views')) {
            $generator = new $this->container['generator.view']($this->config,$this->commandData,$this->container['files']);
            $generator->generate();
        }
    }


    private function rollbackCommonItems()
    {
        $generator = new $this->container['generator.controller']($this->config,$this->commandData,$this->container['files']);
        $generator->rollback();

        $generator = new $this->container['generator.view']($this->config,$this->commandData,$this->container['files']);
        $generator->rollback();
    }
    private function rollbackScaffoldItems()
    {
        $generator = new $this->container['generator.model']($this->config,$this->commandData,$this->container['files']);
        $generator->rollback();

        $generator = new $this->container['generator.library']($this->config,$this->commandData,$this->container['files']);
        $generator->rollback();

    }



    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
//            ['fieldsFile', null, InputOption::VALUE_REQUIRED, 'Fields input as json file'],
//            ['jsonFromGUI', null, InputOption::VALUE_REQUIRED, 'Direct Json string while using GUI interface'],
            ['tableName', null, InputOption::VALUE_REQUIRED, 'Table Name'],
//            ['fromTable', null, InputOption::VALUE_NONE, 'Generate from existing table'],
//            ['save', null, InputOption::VALUE_NONE, 'Save model schema to file'],
            ['primary', null, InputOption::VALUE_REQUIRED, 'Custom primary key'],
            ['softdelete', null, InputOption::VALUE_REQUIRED, 'open softdelete'],
            ['timestamp', null, InputOption::VALUE_NONE, 'auto time maintain'],
            ['rollback', null, InputOption::VALUE_NONE, 'delete scaffold'],
//            ['paginate', null, InputOption::VALUE_REQUIRED, 'Pagination for index.blade.php'],
//            ['skip', null, InputOption::VALUE_REQUIRED, 'Skip Specific Items to Generate (migration,model,controllers,api_controller,scaffold_controller,repository,requests,api_requests,scaffold_requests,routes,api_routes,scaffold_routes,views,tests,menu,dump-autoload)'],
//            ['datatables', null, InputOption::VALUE_REQUIRED, 'Override datatables settings'],
//            ['views', null, InputOption::VALUE_REQUIRED, 'Specify only the views you want generated: index,create,edit,show'],
//            ['relations', null, InputOption::VALUE_NONE, 'Specify if you want to pass relationships for fields'],
        ];
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'Singular Model name'],
        ];
    }
}