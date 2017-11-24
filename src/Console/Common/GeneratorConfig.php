<?php

namespace OutSource\Console\Common;

use OutSource\Config\Config;
use Symfony\Component\Console\Input\InputInterface;

/**
 * 根据传进来的commandData和config准备生成的各种数据
 *
 * @param CommandData &$commandData
 * @param Config $config
 */
class GeneratorConfig
{
    public $config; //存放基本的配置项 比如controller model view

    protected $commandData;

    public $pathViews;

    /* Model Names */
    public $mName;

    public $softDelete = false;

    public $timeStamp = false;

    /**
     * @var string 
     */
    public $primaryName;

    public $tableName;


    /* Generator Options */
    public $options;

    /* Generator AddOns */
    public $addOns;

    public $prefixes;


    public function __construct(CommandData &$commandData,Config $config)
    {
        $this->config = $config;
        $this->commandData = $commandData;
    }

    public function init(CommandData &$commandData, $options = null)
    {
        $this->mName = $commandData->modelName;
        $this->prepareAddOns(); //准备menu的地址
        $this->preparePrefixes();
        $this->preparePrimaryName();
        $this->prepareTableName();
        $this->prepareModel();
        //        $commandData = $this->loadDynamicVariables($this->commandData);
    }


    public function preparePrefixes()
    {
        $this->prefixes['view'] =  $this->config->get('view');
    }

    public function prepareAddOns()
    {
        $this->addOns['menu.enabled'] = "";
        $this->addOns['menu.menu_file'] = "";
    }

    public function preparePrimaryName()
    {
        if ($this->getOption('primary')) {
            $this->primaryName = $this->getOption('primary');
        } else {
            $this->primaryName = 'id';
        }
        $this->config->set('primary', $this->primaryName);
    }

    public function prepareModel()
    {
        if ($this->getOption('softdelete')) {
            $this->softDelete = $this->getOption('softdelete');
        }

        if ($this->getOption('timestamp')) {
            $this->timeStamp = $this->getOption('timestamp');
        }
    }



    public function prepareTableName()
    {
        if ($this->getOption('tableName')) {
            $this->tableName = $this->getOption('tableName');
        } else {
            $this->tableName = $this->mName;
        }

        $this->config->set('tableName', $this->tableName);
    }


    public function getOption($option)
    {
        if (isset($this->options[$option])) {
            return $this->options[$option];
        }

        return false;
    }

    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }


    public function setOptions(InputInterface $input)
    {
        foreach ($input->getOptions() as $key => $option) {
            if($option) {
                $this->setOption($key, $option);
            }
        }
    }

    public function loadDynamicVariables(CommandData &$commandData)
    {

        $commandData->addDynamicVariable('$NAMESPACE_MODEL$', $this->config->get('model'));
        $commandData->addDynamicVariable('$PRIMARY_KEY_NAME$', $this->primaryName);
        $commandData->addDynamicVariable('$MODEL_NAME$', $this->mName);

        if (!empty($this->prefixes['view'])) {
            $commandData->addDynamicVariable('$VIEW_PREFIX$', str_replace('/', '.', $this->prefixes['view']).'.');
        } else {
            $commandData->addDynamicVariable('$VIEW_PREFIX$', '');
        }

        if (!empty($this->prefixes['public'])) {
            $commandData->addDynamicVariable('$PUBLIC_PREFIX$', $this->prefixes['public']);
        } else {
            $commandData->addDynamicVariable('$PUBLIC_PREFIX$', '');
        }
        return $commandData;
    }

    public function get($key)
    {
        return $this->config->get($key);
    }

    public function getViewsPath($views)
    {
        $viewPath = $this->get('applicationTemplates');
        if(file_exists($viewPath.$views)) {
            return $viewPath.$views;
        } else {
            $viewPath = $this->get('systemTemplates');
            return $viewPath.$views;
        }
    }
}