<?php

class Rule
{
    public $ruleName = "";

    public $ruleParamter = '';

    public function __construct($ruleName , $ruleParamter = '')
    {
        $this->ruleName = $ruleName;
        $this->ruleParamter = $ruleParamter;
    }

}