<?php

namespace OutSource\Console\Generators\Contracts;

interface GeneratorInterface
{
    /**
     * 生成函数
     */
    public function generate();

    /**
     * 回滚函数
     */
    public function rollback();

}