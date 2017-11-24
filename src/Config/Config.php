<?php

namespace OutSource\Config;

use OutSource\Kernel\Support\Collection;
use OutSource\Config\CIConfig;
use Symfony\Component\Console\Exception\LogicException;

/**
 * Config.php.
 *
 * @author    lideshun  <584309598@qq.com>
 * @copyright 2017 lideshun <584309598@qq.com>
 *
 */
class Config extends Collection
{
    /**
     * 用户的默认驱动框架
     */
    private $_dirver = 'CI';

    /**
     * Construct function
     *
     * @param array $items 用户自定义配置参数
     *
     * @return void
     */
    public function __construct($items)
    {
        $ci_configs = array();

        if(isset($items['driver'])) {
            switch($items['driver']) {
            case 'CI':
                $config = new CIConfig($items);
                $ci_configs = $config->getConfigs();
                break;
            default:
                $this->_errorConfig();
                break;
            }
        } else {
            $config = new CIConfig($items);
            $ci_configs = $config->getConfigs();
        }

        parent::__construct($ci_configs);
    }

    /**
     * Error Exception
     *
     * @return throw error
     */
    private function _errorConfig()
    {
        throw new LogicException("config driver not found");
    }

}