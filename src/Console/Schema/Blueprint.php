<?php

namespace OutSource\Console\Schema;

use InvalidArgumentException;

class Blueprint
{


    /**
     * The table the blueprint describes.
     *
     * @var string
     */
    public $table;

    public $primaryKey;

    public $softDelete = false;

    public $timestamp;

    public $columns = [];

    public $htmlType = [];

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    public $rule = [];


    public function __construct($table , $primaryKey = null)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    public function addColumn($columnName , $htmlType ,array $rule)
    {
        if(empty($columnName))
            throw new InvalidArgumentException("columnName invalid ");

        $this->columns[] = $columnName;
        $this->htmlType[$columnName] =  $htmlType;
        $this->rule[$columnName] =  $rule;

        return $this;
    }

    public function activeSoftDelete($value)
    {
        $this->softDelete = $value;
    }

    public function activeTimeStamp($value)
    {
        $this->timestamp = $value;
    }


}
