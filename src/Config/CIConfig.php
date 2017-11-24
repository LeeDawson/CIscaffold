<?php

namespace OutSource\Config;

use InvalidArgumentException;
use OutSource\Config\Contracts\ConfigInterface;

class CIConfig implements ConfigInterface
{
    protected $basePath = "";
    /**
     * 项目名称
     */
    protected $rootPath = "application";
    /**
     * 默认的CI路径
     */
    protected $ciPath = [
        "controller" => "controllers",
        "model" => "models",
        "library" => "libraries",
        "views" => "views",
        "driver" => "CI",
        "applicationTemplates" => "",
        "systemTemplates" => "",
        "config" => "config",
        "modules" => "admin" ,
        "core" => "core" ,
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
        $this->ciPath['applicationTemplates'] = $applicaitonTemplate;
        $this->ciPath['systemTemplates'] = $this->templatePath($items['applicationPath']);
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
        $this->ciPath =  array_merge($this->ciPath, $item);
    }

    public function getConfigs()
    {
        return [
            "namespace" => "application",
            "controller" =>  $this->rootPaths($this->ciPath['controller']),
            "model" => $this->rootPaths($this->ciPath['model']),
            "library" => $this->rootPaths($this->ciPath['library']),
            "views" => $this->rootPaths($this->ciPath['views']),
            "core" => $this->rootPaths($this->ciPath['core']),
            "router" => "",
            "config" => $this->rootPaths($this->ciPath['config']),
            "driver" => $this->ciPath['driver'],
            "applicationTemplates" => $this->ciPath['applicationTemplates'],
            "systemTemplates" =>  $this->ciPath['systemTemplates'],
            "basePath" => $this->basePath,
            "modules" =>  $this->ciPath['modules'].DIRECTORY_SEPARATOR,
            "schema" => $this->basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->ciPath['schema'] . DIRECTORY_SEPARATOR ,
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
        return $applicationPath.DIRECTORY_SEPARATOR.'Console'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'CITemplates'.DIRECTORY_SEPARATOR;
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