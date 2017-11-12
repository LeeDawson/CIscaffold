<?php

namespace OutSource\Console\Commands\Scaffold;

use OutSource\Console\Commands\BaseCommand;
use OutSource\Console\Common\CommandData;
use OutSource\Console\Common\GeneratorConfig;
use OutSource\Console\Generators\CIGenerator\ControllerGenerator;
use OutSource\Console\Generators\CIGenerator\ModelGenerator;
use Psr\Log\InvalidArgumentException;
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
            //从schema 收集数据
            $this->generScaffoldBySchema();

            $this->generScaffold();
        } else {
            //从用户输入收集数据
            parent::handle();
            $this->generScaffold();
        }
    }

    protected function generScaffoldBySchema()
    {
        $invoke = $this->loadSchema();

        $this->parseInput($invoke);

        $this->commandData->getInputFormSchema($invoke);

        $this->config->init($this->commandData);
    }

    private function parseInput($invoke)
    {
       $invoke->table && $this->config->setOption("tableName",$invoke->table);
       $invoke->primaryKey && $this->config->setOption("primary",$invoke->primaryKey);
       $invoke->softDelete && $this->config->setOption("softdelete",$invoke->softDelete);
       $invoke->timestamp &&  $this->config->setOption("timestamp",$invoke->timestamp);
    }

    private function loadSchema()
    {
        $schemaName = "create_". ucfirst($this->option('schema')). "_schema";
        $schemaPath = $this->config->get('schema').$schemaName.".php";
        if(file_exists($schemaPath))
            include $schemaPath;
        else
            throw new InvalidArgumentException("未能找到".$schemaPath.'这个文件');

        $class =  new $schemaName();
        $invoke = $class->up();

        return $invoke;
    }

    protected function generScaffold()
    {
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
            $generator = new $this->container['generator.model']($this->config , $this->commandData , $this->container['files']);
            $generator->generate();
        }

        if (!$this->isSkip('library')) {
            $generator = new $this->container['generator.library']($this->config , $this->commandData , $this->container['files']);
            $generator->generate();
        }
    }

    private function generateScaffoldItems()
    {
        if (!$this->isSkip('controllers') ) {
            $generator = new $this->container['generator.controller']($this->config , $this->commandData , $this->container['files']);
            $generator->generate();
        }

        if (!$this->isSkip('views')) {
            $generator = new $this->container['generator.view']($this->config , $this->commandData , $this->container['files']);
            $generator->generate();
        }
    }

    /**
     * 删除掉 controller 和 view
     */
    private function rollbackCommonItems()
    {
        $generator = new $this->container['generator.controller']($this->config , $this->commandData , $this->container['files']);
        $generator->rollback();

        $generator = new $this->container['generator.view']($this->config , $this->commandData , $this->container['files']);
        $generator->rollback();
    }

    /**
     * 删除掉模型和类库
     *
     */
    private function rollbackScaffoldItems()
    {
        $generator = new $this->container['generator.model']($this->config , $this->commandData , $this->container['files']);
        $generator->rollback();

        $generator = new $this->container['generator.library']($this->config , $this->commandData , $this->container['files']);
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
            ['schema', null, InputOption::VALUE_REQUIRED, 'from schema generator'],
            ['timestamp', null, InputOption::VALUE_REQUIRED, 'order by item'],
            ['rollback', null, InputOption::VALUE_NONE, 'delete scaffold'],
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