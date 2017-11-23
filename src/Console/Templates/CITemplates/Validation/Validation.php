<?php

include_once __DIR__.DIRECTORY_SEPARATOR.'Rule.php';

class Validation
{

    private static $correctRule = [
        "required"   => "该属性必须存在",
        "min"        => "该属性必须小于设置值",
        "max"        => "该属性不能大于设置值",
        "number"     => "该属性必须是数字" ,
        "email"      => "该属性必须是邮箱格式",
        "ip"         => "该属性必须是IP地址格式",
        "string"     => "该属性必须是字符串",
        "allowEmpty" => ""
    ];

    /**
     * 用验证规则来验证数据
     *
     * @param array $preDatas 准备验证的数据
     * @param array  $rules  准备验证的规则
     *
     * @return array  [ "result" => true , "msg" => "" ,"correctData" => ""];
     */
    public static function checkDataRule($preDatas, $rules)
    {
        $checkData = [ "result" => true , "msg" => "" ];
        $correctData = ['correctData' => ''];

        if(empty($preDatas))
            throw new LogicException("待检查数组不能为空");

        foreach ($rules as $key => $rule) {
            if( isset($preDatas[$key]) ) {
                $waitData = $preDatas[$key]; //需要检查的数据
                $checkData = Validation::startCheck($waitData , Validation::handleRule($rule) , $key);
                if(!$checkData['result'])
                    return $checkData;

                $correctData['correctData'][$key] = $waitData;
            }
        }

        $correctData = array_merge($preDatas , $correctData['correctData']);
        $checkData['correctData'] = $correctData;

        return  $checkData;
    }

    public static function checkDataByRule($preDatas, $rules)
    {

        $checkData = [ "result" => true , "msg" => ''];
        $correctData = ['correctData' => ''];

        if(empty($preDatas))
            throw new LogicException("待检查数组不能为空");

        foreach ($rules as $key => $rule) {
            if( isset($preDatas[$key]) ) {  // 输入的数据如果存在规则的字段 成功,不存在报错
                $waitData = $preDatas[$key]; //等待被验证的值
                $checkData = Validation::startCheck( $waitData , Validation::handleRule($rule) , $key);

                if(!$checkData['result'])
                    return $checkData;

                $correctData['correctData'][$key] = $waitData;
            } else {  //如果用户规则中的数据没有
                $checkData['result'] = false;
                $checkData['msg'] = $key .self::$correctRule['required'];
                return $checkData;
            }
        }

        return array_merge($checkData , $correctData);
    }

    /**
     * 处理用户输入的验证规则 正确则验证 不正确则过滤掉
     *
     * @param array $rules
     *
     * @return array correct rules
     */
    protected static function handleRule($val)
    {
        $correct = [];
        $rules = explode('|',$val);

        if(empty($rules))
            return [];

        foreach ($rules as $key => $rule) {
            if( preg_match("/^(.+):(\d+)/" , $rule , $match ) && count($match) == 3 ){
                if(in_array( $match[1] , array_keys(Validation::$correctRule)) )
                    $correct[] =  new Rule($match[1] , $match[2]);
            } else if( in_array($rule , array_keys(Validation::$correctRule)) ){
                $correct[] =  new Rule($rule);
            }
        }

        return $correct;
    }

    protected static function startCheck(&$waitData , $preRules , $name)
    {
        foreach ($preRules as $preRule) {
            $checkReuslt = forward_static_call_array( [ self::class , $preRule->ruleName] , [ $waitData , $preRule->ruleParamter]);
            if(!$checkReuslt)
                return [ 'result' => false , 'msg' => $name.Validation::$correctRule[$preRule->ruleName] ];

            $waitData = $checkReuslt;
        }

        return  ['result' => true , 'msg' => ''];
    }

    private static function required($val , $param = [])
    {
        if(empty($val))
            return false;

        return $val;
    }

    private static function max($val , $param)
    {
        if(strlen($val) > $param)
            return false;

        return $val;
    }

    private static function number($val , $param )
    {
        if($result = filter_var($val , FILTER_VALIDATE_INT)){
            return $val;
        }
        return false;
    }

    public static function string( $val , $param)
    {
        if($result = filter_var($val , FILTER_SANITIZE_STRING))
            return $result;

        return false;
    }

    private static function min($val , $param )
    {
        if(strlen($val) < $param)
            return $val;

        return false;
    }

    public static function email( $val , $param)
    {
        if($result = filter_var($val , FILTER_VALIDATE_EMAIL))
            return $val;

        return false;
    }

    public function ip ($val , $param)
    {
        if(filter_var($val , FILTER_VALIDATE_IP))
            return $val;

        return false;
    }


    public function allowEmpty($val , $param)
    {
        return $val;
    }


}