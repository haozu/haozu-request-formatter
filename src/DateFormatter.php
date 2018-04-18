<?php
namespace Haozu\RequestFormatter;

/**
 * DateFormatter 格式化日期
 */
class DateFormatter extends BaseFormatter implements FormatterInterface 
{   
    /*
     * 格式化类型
     */ 
    protected $format;

    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'date',
     *          'default'   => '',
     *          'format'    => 'timestamp', 
     *          'min'       => '',
     *          'max'       => ''
     *        ];
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
        $this->format    = isset($rule['format']) ? strtolower($rule['format']) : '' ;
    }


    /**
     * 对日期进行格式化
     *
     * @return timesatmp/string 格式化后的变量
     */
    public function parse() {
        $result = $this->value;
        if ($this->format == 'timestamp') {
            $this->value = strtotime($this->value);
            if ($this->value <= 0) {
                $this->value = 0;
            }
            if (isset($this->min) && !is_numeric($this->min)) {
                $this->min = strtotime($this->min);
            }
            if (isset($this->max) && !is_numeric($this->max)) {
                $this->max = strtotime($this->max);
            }
            $result = $this->filterByRange();
        }
        return $result;
    }
}