<?php

namespace OutSource\Config;

use InvalidArgumentException;
use OutSource\Config\Contracts\ConfigInterface;

class YafConfig implements ConfigInterface
{
    protected $basePath = "";
    /**
     * 项目名称
     */
    protected $rootPath = "application";
    /**
     * 默认的CI路径
     */
    protected $yafPath = [
        "controller" => "controllers",
        "model" => "models",
        "library" => "library",
        "views" => "views",
        "driver" => "Yaf",
        "applicationTemplates" => "",
        "systemTemplates" => "",
        "config" => "conf",
        "modules" => "modules" ,
        "schema" => "schema"
    ];

    public function __construct(array $items)
    {
        $this->setSystemPath($items);
        $this->setBasePath($this->getBaseUrl($items));
        $this->setTemplates($items);
    }

    protected function setTemplates($items)
    {
        $applicaitonTemplate = $this->basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this->yafPath['applicationTemplates'] = $applicaitonTemplate;
        $this->yafPath['systemTemplates'] = $this->templatePath($items['applicationPath']);
    }

    protected function getBaseUrl($item)
    {
        if(empty($item) || !isset($item['basePath']) || ( isset($item['basePath']) && empty($item['basePath']))) {
            throw new InvalidArgumentException("basePath must exist");
        }

        return realpath($item['basePath']);
    }

    protected function setBasePath($basePath)
    {
        return $this->basePath = $basePath;
    }

    /**
     * 合并用户自定义数据
     *
     * @param array $args
     */
    protected function setSystemPath($item)
    {
        $this->yafPath =  array_merge($this->yafPath, $item);
    }

    public function getConfigs()
    {
        return [
            "namespace" => "application",
            "controller" =>  $this->rootPaths($this->yafPath['controller']),
            "model" => $this->rootPaths($this->yafPath['model']),
            "library" => $this->rootPaths($this->yafPath['library']),
            "views" => $this->rootPaths($this->yafPath['views']),
            "router" => "",
            "config" => $this->basePath.DIRECTORY_SEPARATOR.$this->yafPath['config'],
            "driver" => $this->yafPath['driver'],
            "applicationTemplates" => $this->yafPath['applicationTemplates'],
            "systemTemplates" =>  $this->yafPath['systemTemplates'],
            "basePath" => $this->basePath,
            "modules" =>  $this->rootPaths($this->yafPath['modules']),
            "schema" => $this->basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->yafPath['schema'] . DIRECTORY_SEPARATOR ,
        ];
    }

    protected function rootPaths($path)
    {
        if(!$this->checkPath($path)) {
            throw new InvalidArgumentException($path."error");
        }
        return $this->basePath . DIRECTORY_SEPARATOR . $this->rootPath . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
    }

    protected function templatePath($applicationPath)
    {
        return $applicationPath.DIRECTORY_SEPARATOR.'Console'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'YafTemplates'.DIRECTORY_SEPARATOR;
    }

    private function checkPath($path)
    {
        $paths = explode('/', $path);
        if(count($path) > 1) {
            return false;
        }

        return trim($path, DIRECTORY_SEPARATOR);
    }

}