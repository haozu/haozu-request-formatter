<?php
namespace Haozu\RequestFormatter;

use Haozu\RequestFormatter\Exception\BadRequestException;
use Haozu\RequestFormatter\Exception\InternalServerErrorException;

/**
 * BaseFormatter 公共基类
 *
 * - 提供基本的公共功能，便于子类重用
 */
class BaseFormatter 
{

    /*
     * 规则字段
     */
    protected $name;

    /*
     * 字段值
     */
    protected $value;

    /*
     * 最小值
     */ 
    protected $min;

    /*
     * 最大值
     */ 
    protected $max;


    /**
     * 初始化 这里只初始化公共属性
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        if(isset($rule['min'])){
            $this->min = $rule['min'];
        }

        if(isset($rule['max'])){
            $this->max = $rule['max'];
        }

        $this->value  = $value;
        $this->name   = isset($rule['name']) ? $rule['name'] : '';
    }

    /**
     * 根据范围进行控制
     *
     * @return miexd            格式后的变量
     */ 
    protected function filterByRange() 
    {
        $this->filterRangeMinLessThanOrEqualsMax();
        $this->filterRangeCheckMin();
        $this->filterRangeCheckMax();
        return $this->value;
    }

    /**
     * 范围最小值是否大于最大值
     *
     */
    protected function filterRangeMinLessThanOrEqualsMax() 
    {
        if (isset($this->min) && isset($this->max) && $this->min > $this->max) {
            throw new InternalServerErrorException(
                sprintf('min should <= max, but now %s min = %s and max = %s', 
                    $this->name, $this->min, $this->max)
            );
        }
    }

    /**
     * 范围最小值是否在规定值内
     *
     */
    protected function filterRangeCheckMin() 
    {
        if (isset($this->min) && $this->value < $this->min) {
            throw new BadRequestException(
                sprintf('%s should >= %s, but now %s = %s', 
                    $this->name, $this->min, $this->name,$this->value)
            );
        }
    }

    /**
     * 范围最小值是否在规定值内
     *
     */
    protected function filterRangeCheckMax() 
    {
        if (isset($this->max) && $this->value > $this->max) {
            throw new BadRequestException(
                sprintf('%s should <= %s, but now %s = %s', 
                $this->name, $this->max, $this->name, $this->value)
            );
        }
    }


}