<?php
namespace Haozu\RequestFormatter;

use Haozu\RequestFormatter\Exception\InternalServerErrorException;
use Haozu\RequestFormatter\Exception\BadRequestException;

/**
 * Formatter 格式化数组枚举类型
 */
class ArrayEnumFormatter extends BaseFormatter implements FormatterInterface 
{


    /*
     * 格式化类型
     */ 
    protected $format;

    /*
     * 分割符
     */ 
    protected $separator;


    /*
     * 范围
     */ 
    protected $range;


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
     *          'range'    => [1,2,3], 
     *        ];
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
        $this->format    = isset($rule['format']) ? strtolower($rule['format']) : '' ;
        $this->separator = isset($rule['separator']) ? $rule['separator'] : ',' ;
        if (!isset($rule['range'])) {
            throw new InternalServerErrorException(sprintf("miss %s's arrayEnum range",$this->name));
        }
        if (empty($rule['range']) || !is_array($rule['range'])) {
            throw new InternalServerErrorException(sprintf("%s's arrayEnum range can not be empty",$this->name));
        }
        $this->range    = $rule['range'];
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
        //检查数量以及范围是否符合
        $this->filterByRange();
        if (array_diff($result,$this->range)) {
            throw new BadRequestException(
                sprintf('%s should be in %s, but now %s = %s', 
                    $this->name , implode('/', $this->range), $this->name,implode('/', $result))
            );
        }
        return $result;
    }
}
