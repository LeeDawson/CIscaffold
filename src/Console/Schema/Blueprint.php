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
    protected $table;

    protected $primaryKey;

    protected $tmpColumn;

    /**
     * The columns that should be added to the table.
     *
     * @var array
     */
    protected $columns = [];

    protected $htmlType = [];

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    protected $rule = [];


    public function __construct($table , $primaryKey = null)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    public function addColumn($columnName , $htmlType , $rule)
    {
        if(empty($columnName))
            throw new InvalidArgumentException("columnName invalid ");

        $this->columns[] = $columnName;
        $this->htmlType[] = [ $columnName => $htmlType ];
        $this->rule[] = [$columnName => $rule ];

        return $this;
    }


}
