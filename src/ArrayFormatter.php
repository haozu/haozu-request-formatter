<?php
namespace Haozu\RequestFormatter;

use Haozu\Exception\BadRequestException;

/**
 * Formatter Array 格式化数组
 */
class ArrayFormatter extends BaseFormatter implements FormatterInterface 
{


    /*
     * 格式化类型
     */ 
    protected $format;

    /*
     * 分割符
     */ 
    protected $separator;


    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'array',
     *          'default'   => '',
     *          'format'    => 'json/explode', 
     *          'separator' => '', 
     *          'min'       => '',
     *          'max'       => ''
     *        ];
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
        $this->format    = isset($rule['format']) ? strtolower($rule['format']) : '' ;
        $this->separator = isset($rule['separator']) ? $rule['separator'] : ',' ;
        
    }


    /**
     * 对数组格式化/数组转换
     *
     * @return array
     */
    public function parse() 
    {
        $result = $this->value;
        //如果不是数组 格式化数据
        if (!is_array($result)) {
            if ($this->format == 'explode') {
                $result = explode($this->separator,$result);
            } 
            else if ($this->format == 'json') {
                $result = json_decode($result, TRUE);
                if (!empty($value) && $result === NULL) {
                    throw new BadRequestException(sprintf('%s illegal json data',$this->name));
                }
            } 
            else {
                $result = array($result);
            }
        }
        $this->value = count($result);
        $this->filterByRange();
        return $result;
    }
}
