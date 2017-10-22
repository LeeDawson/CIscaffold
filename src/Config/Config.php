<?php

namespace OutSource\Config;

use OutSource\Kernel\Support\Collection;
use OutSource\Config\CIConfig;
use Symfony\Component\Console\Exception\LogicException;

class Config extends Collection
{
    /**
     * 用户的默认驱动框架
     */
    private $dirver = 'CI';

    public function __construct($items)
    {
        $ci_configs = array();

        if(isset($items['driver'])){
            switch($items['driver']){
                case 'CI':
                    $config = new CIConfig($items);
                    $ci_configs = $config->getConfigs();
                    break;
                default:
                    $this->errorConfig();
                    break;
            }
        }
        else {
            $config = new CIConfig($items);
            $ci_configs = $config->getConfigs();
        }

        parent::__construct( $ci_configs );
    }

    private function errorConfig(){
        throw new LogicException("config driver not found");
    }

}