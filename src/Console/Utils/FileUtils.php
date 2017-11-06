<?php

namespace OutSource\Console\Utils;

use Symfony\Component\Console\Exception\LogicException;

class FileUtils
{

    public static function getTemplateScaffoldPath($systemTemplatePath , $name)
    {
        $systemTemplatePath = $systemTemplatePath . DIRECTORY_SEPARATOR . 'Scaffold' . DIRECTORY_SEPARATOR . $name;

        $result = file_get_contents($systemTemplatePath);

        if(empty($result)){
            throw new LogicException($name.'template not found,please check PATH');
        }

        return $result;
    }

    public static function getTemplateModulesPath($systemTemplatePath , $path)
    {
        $systemTemplatePath = $systemTemplatePath . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $path;

        $result = file_get_contents($systemTemplatePath);

        if(empty($result)){
            throw new LogicException($path.'template not found,please check PATH');
        }

        return $result;
    }

    public static function getTemplateByPath($systemTemplatePath)
    {

        $result = file_get_contents($systemTemplatePath);

        if(empty($result)){
            throw new LogicException($systemTemplatePath.'template not found,please check PATH');
        }

        return $result;
    }




    public static function fileExistWithFetch($config,$name)
    {
        $applicaitonTemplatePath = $config->get('applicationTemplates').'Layouts'.DIRECTORY_SEPARATOR.$name;
        $systemTemplatePath = $config->get('systemTemplates').'Layouts'.DIRECTORY_SEPARATOR.$name;

        if(file_exists($applicaitonTemplatePath))
            return file_get_contents($applicaitonTemplatePath);

        if(file_exists($systemTemplatePath))
            return file_get_contents($systemTemplatePath);

        throw new LogicException('没有找到分页模板');

    }


    public static function createFile($path, $fileName, $contents)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = $path.$fileName;

        file_put_contents($path, $contents);
    }

    public static function deleteFile($path, $fileName)
    {
        if (file_exists($path.$fileName)) {
            return unlink($path.$fileName);
        }
        return false;
    }
}