<?php

namespace OutSource\Config\Contracts;

interface ConfigInterface
{
    /**
     * 获取config信息
     *
     * @return array
     */
    public function getConfigs();

}