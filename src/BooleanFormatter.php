<?php
namespace Haozu\RequestFormatter;

/**
 * BooleanFormatter 格式化布尔值
 */
class BooleanFormatter extends BaseFormatter implements FormatterInterface 
{   

    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'boolean',
     *          'default'   => '',
     *        ];
     * 
     */
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
    }


    /**
     * 对布尔型进行格式化
     *
     * @param  mixed $value 变量值
     * @return boolean
     */
    public function parse() {
        $result = $this->value;
        if (!is_bool($this->value)) {
            if (is_numeric($this->value)) {
                $result = $this->value > 0 ? TRUE : FALSE;
            } 
            else if (is_string($this->value)) {
                $result = in_array(strtolower($this->value), array('ok', 'true', 'success', 'on', 'yes')) 
                    ? TRUE : FALSE;
            } 
            else {
                $result = $this->value ? TRUE : FALSE;
            }
        }
        return $result;
    }
}