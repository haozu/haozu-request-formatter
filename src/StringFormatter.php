<?php
namespace Haozu\RequestFormatter;

use Haozu\RequestFormatter\Exception\BadRequestException;
/**
 * StringFormatter  格式化字符串
 */
class StringFormatter extends BaseFormatter implements FormatterInterface 
{   
    /**
     * 正则
     */
    protected $regex;


    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'String',
     *          'default'   => '',
     *          'regex'     => '正则'
     *          'min'       => '',
     *          'max'       => ''
     *        ];
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
        $this->regex = isset($rule['regex'])?$rule['regex']:'';
    }

    /**
     * 对字符串进行格式化
     *
     * @return string 格式化后的变量
     */
    public function parse() {
        $result = $this->value;
        $this->value = mb_strlen($this->value);
        $this->filterByRange();
        $this->filterByRegex($result);
        return $result;
    }

    /**
     * 进行正则匹配
     */
    protected function filterByRegex($value) {
        if (empty($this->regex)) {
            return;
        }
        if (preg_match($this->regex, $value) <= 0) {
            throw new BadRequestException(sprintf('%s can not match regex',$this->name));
        }
    }
}