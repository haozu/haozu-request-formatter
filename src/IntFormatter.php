<?php
namespace Haozu\RequestFormatter;

/**
 * IntFormatter 格式化整型
 */
class IntFormatter extends BaseFormatter implements FormatterInterface 
{   

    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'Int',
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
     * 对整型进行格式化
     *
     * @return int/string 格式化后的变量
     */
    public function parse() {
        $this->value = intval($this->value);
        return intval($this->filterByRange());
    }
}