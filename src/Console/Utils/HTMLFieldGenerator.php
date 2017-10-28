<?php

namespace OutSource\Console\Utils;

use OutSource\Console\Common\GeneratorField;

class HTMLFieldGenerator
{

    public static function generateHTML(GeneratorField $field, $config, $file )
    {
        $fieldTemplate = '';

        switch ($field->htmlType) {
            case 'text':
                $filePath =  $config->getViewsPath('Fields/text.stub');
                $templateData = $file->get($filePath);
                $templateData = str_replace('$KEYS$' , $field->name , $templateData);
                $templateData = str_replace('$VALUES$' , sprintf('<?php echo @$data["%s"]?>',$field->name) , $templateData);

                return $templateData;
                break;
            case 'textarea':
            case 'date':
            case 'file':
                $filePath =  $config->getViewsPath('Fields/file.stub');
                $templateData = $file->get($filePath);
                $templateData = str_replace('$FILEIMGID$' , $field->name.'_img' , $templateData);
                $templateData = str_replace('$FILENAMEID$' , $field->name , $templateData);
                return $templateData;
            case 'radio':
                $radioGroupPath =  $config->getViewsPath('Fields/radio_group.stub');
                $radioGroupData = $file->get($radioGroupPath);

                $radios = HTMLFieldGenerator::prepareKeyValueArrFromLabelValueStr($field->htmlValues);

                $radioButtons = [];

                foreach ($radios as $key => $radioValue) {
                    $radioPath =  $config->getViewsPath('Fields/radio.stub');
                    $radioData = $file->get($radioPath);
                    $radioData = str_replace('$KEY$' , $key , $radioData);
                    $radioData = str_replace('$VALUE$' , $radioValue  , $radioData);
                    $radioButtons[] = $radioData;
                }
                $radioGroupData = str_replace('$KEY$' , $field->name  , $radioGroupData);
                $radioGroupData = str_replace('$RADIOVALUES$' , implode(PHP_EOL, $radioButtons)  , $radioGroupData);

                return $radioGroupData;
                break;
            case 'select':
                $selectGroupPath =  $config->getViewsPath('Fields/select_group.stub');
                $selectGroupData = $file->get($selectGroupPath);

                $radios = HTMLFieldGenerator::prepareKeyValueArrFromLabelValueStr($field->htmlValues);
                $radioButtons = [];

                foreach ($radios as $key => $radioValue) {
                    $selectPath =  $config->getViewsPath('Fields/select.stub');
                    $selectData = $file->get($selectPath);
                    $selectData = str_replace('$KEY$' , $key , $selectData);
                    $selectData = str_replace('$VALUE$' , $radioValue  , $selectData);
                    $radioButtons[] = $selectData;
                }
                $selectGroupData = str_replace('$KEY$' , $field->name  , $selectGroupData);
                $selectGroupData = str_replace('$OPTIONS$' , implode(PHP_EOL, $radioButtons)  , $selectGroupData);
                return $selectGroupData;
                break;
            default :
                break;
        }

        return $fieldTemplate;
    }

    public static function prepareKeyValueArrFromLabelValueStr($values)
    {
        $arr = [];

        foreach ($values as $value) {
            $labelValue = explode(':', $value);

            if (count($labelValue) > 1) {
                $arr[$labelValue[0]] = $labelValue[1];
            } else {
                $arr[$labelValue[0]] = $labelValue[0];
            }
        }

        return $arr;
    }

//    private static function getFieldsPath($views)
//    {
//        $viewPath = $this->commandConfig->get('applicationTemplates');
//        if(file_exists($viewPath.$views)){
//            return file_get_contents($viewPath.$views);
//        } else {
//            $viewPath = $this->commandConfig->get('systemTemplates');
//            return file_get_contents($viewPath.$views);
//        }
//    }




}
