<?php
namespace Haozu\RequestFormatter;

/**
 * FloatFormatter 格式化浮点类型
 */
class FloatFormatter extends BaseFormatter implements FormatterInterface 
{   
  
   

    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'Float',
     *          'default'   => '',
     *          'min'       => '',
     *          'max'       => ''
     *        ];
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
    }

    /**
     * 对浮点型进行格式化
     *
     * @return float/string 格式化后的变量
     */
    public function parse() {
        $this->value = floatval($this->value);
        return floatval($this->filterByRange());
    }
}