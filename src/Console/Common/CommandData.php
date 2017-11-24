<?php

namespace OutSource\Console\Common;

use OutSource\Console\Schema\Blueprint;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OutSource\Console\Utils\GeneratorFieldsInputUtil;
/**
 *  拿到命令行中数据存入
 */
class CommandData
{
    public static $COMMAND_TYPE_SCAFFOLD = 'scaffold';

    /**
     * @var string 
     */
    public $modelName;

    public $commandType;

    /**
     * @var GeneratorConfig 
     */
    public $config;

    /**
     * @var GeneratorField[] 
     */
    public $fields = [];

    /**
     * @var Command 
     */
    public $commandObj;

    /**
     * @var array 
     */
    public $dynamicVars = [];

    public $fieldNamesMapping = [];


    /**
     * @var CommandData 
     */
    protected static $instance = null;

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * The input interface implementation.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * The output interface implementation.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    /**
     * @param Command $commandObj
     * @param string  $commandType
     *
     * @return CommandData
     */
    public function __construct(Command $commandObj, $commandType)
    {
        $this->commandObj = $commandObj;
        $this->commandType = $commandType;

        $this->fieldNamesMapping = [
            '$FIELD_NAME_TITLE$' => 'fieldTitle',
            '$FIELD_NAME$'       => 'name',
        ];
    }

    public function addDynamicVariable($name, $val)
    {
        $this->dynamicVars[$name] = $val;
    }


    public function getFields(InputInterface $input)
    {
        $this->fields = [];
        $this->getInputFromConsole($input);

    }

    public function getInputFormSchema(Blueprint $schema)
    {
        $this->addPrimaryKeyBySchema($schema->primaryKey);

        foreach ($schema->columns as $column) {

            //多种类型的html支持
            $fieldInputStr = implode(" ", [$column , $schema->htmlType[$column] , implode(",", $schema->options[$column]) ]);
            $validations = implode("|", $schema->rule[$column]);

            $this->fields[] = GeneratorFieldsInputUtil::processFieldInput(
                $fieldInputStr,
                $validations
            );
        }

    }

    private function getInputFromConsole($input)
    {
        //$this->commandInfo('Specify fields for the model (skip id & timestamp fields, we will add it automatically)');
        $this->commandInfo('字段格式请查看文档');
        $this->commandInfo('输入 "exit" 结束');

        $this->addPrimaryKey($input);

        while (true) {
            $fieldInputStr = $this->commandObj->ask('输入字段格式: (name html_type options)', '');

            if (empty($fieldInputStr) || $fieldInputStr == false || $fieldInputStr == 'exit') {
                break;
            }

            if (!GeneratorFieldsInputUtil::validateFieldInput($fieldInputStr)) {
                $this->commandError('错误的输入. 请重新输入');
                continue;
            }

            $validations = $this->commandObj->ask('输入验证规则: ', false);
            $validations = ($validations == false) ? '' : $validations;
            $this->fields[] = GeneratorFieldsInputUtil::processFieldInput(
                $fieldInputStr,
                $validations
            );
        }

    }

    public function commandInfo($message)
    {
        $this->commandObj->info($message);
    }

    public function commandError($error)
    {
        $this->commandObj->error($error);
    }

    public function commandComment($message)
    {
        $this->commandObj->comment($message);
    }

    public function commandWarn($warning)
    {
        $this->commandObj->warn($warning);
    }

    private function addPrimaryKey(InputInterface $input)
    {
        $primaryKey = new GeneratorField();

        if ($input->getOption('primary')) {
            $primaryKey->name = $input->getOption('primary');
        } else {
            $primaryKey->name = 'id';
        }

        $primaryKey->parseDBType('increments');
        $primaryKey->parseOptions('f,p');

        $this->fields[] = $primaryKey;
    }

    private function addPrimaryKeyBySchema($primary)
    {
        $primaryKey = new GeneratorField();

        if ($primary) {
            $primaryKey->name = $primary;
        } else {
            $primaryKey->name = 'id';
        }

        $primaryKey->parseDBType('increments');
        $primaryKey->parseOptions('f,p');

        $this->fields[] = $primaryKey;
    }

    public function getModelPrimaryKey()
    {
        foreach ($this->fields as $field) {
            if($field->isPrimary) {
                return $field->name;
            }
        }

        throw new LogicException("We can not live without primary key");
    }



}