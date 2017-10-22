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
            case 'email':
            default :
                break;
        }

        return $fieldTemplate;
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
