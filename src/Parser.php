<?php
namespace Haozu\RequestFormatter;

use Haozu\RequestFormatter\Exception\InternalServerErrorException;
use Haozu\RequestFormatter\Exception\BadRequestException;
/**
 * Parser 变量格式化类
 * 
 * - 1、根据字段与预定义变量对应关系，获取变量值
 * - 2、对变量进行类型转换
 * - 3、进行有效性判断过滤
 * - 4、按业务需求进行格式化 
 * 
 * <br>格式规则：<br>
```
 *  array('name' => '', 'type' => 'string', 'default' => '', 'min' => '', 'max' => '', 'regex' => '')
 *  array('name' => '', 'type' => 'int', 'default' => '', 'min' => '', 'max' => '',)
 *  array('name' => '', 'type' => 'float', 'default' => '', 'min' => '', 'max' => '',)
 *  array('name' => '', 'type' => 'boolean', 'default' => '',)
 *  array('name' => '', 'type' => 'date', 'default' => '',)
 *  array('name' => '', 'type' => 'array', 'default' => '', 'format' => 'json/explode', 'separator' => '')
 *  array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
```
 */
class Parser {


    /**
     * 格式化对象
     */    
    private static $formatters = [];
    

    /**
     * 获取格式化对象
     *
     * @param string $class 格式化的类
     * @return mixed
     */
    private static function getFormatter($class) {
        if(!isset(static::$formatters[$class])){
            static::$formatters[$class] = class_exists($class) ? new $class: new \stdClass;
        }
        return static::$formatters[$class];
    }
    
    /**
     * 统一处理
     *
     * @param string $type 类型
     * @param string $value 值
     * @param array $rule 规则配置
     * @return mixed
     */
    private static function formatAllType($type, $value, $rule) {
        $namespace = "\\Haozu\\RequestFormatter\\";
        $formatter = static::getFormatter($namespace.ucfirst($type).'Formatter');
        if (!($formatter instanceof FormatterInterface)) {
            throw new InternalServerErrorException(sprintf('invalid type: %s for rule: %s',  $type, $rule['name']));
        }  
        //格式化类全局唯一每次调用重新初始化属性
        $formatter->onInitialize($value,$rule);
        //解析规则
        $result = $formatter->parse();
        //是否必填统一处理 默认值优先级高于是否必填
        if ($result === NULL && (isset($rule['require']) && $rule['require'])) {
            throw new BadRequestException(sprintf('%s require, but miss',$rule['name']));
        }
        return $result;
    }

    /**
     * 统一格式化操作
     * 扩展参数请参见各种类型格式化操作的参数说明
     *
     * @param array $rule 格式规则：
     * @param array $params 参数列表
     * @return miexd 格式后的变量
     */ 
    public static function format($rule, $params) {
        if (!isset($rule['name'])) {
            throw new InternalServerErrorException('miss name for rule');
        }
        $value = isset($rule['default']) ? $rule['default'] : NULL;
        //默认值处理
        $value = isset($params[$rule['name']]) ? $params[$rule['name']] : $value;
        $type  = isset($rule['type']) ? strtolower($rule['type']) : 'string';
        
        if ($value === NULL) { 
            return $value;
        }
        return static::formatAllType($type, $value, $rule);
    }

    
}