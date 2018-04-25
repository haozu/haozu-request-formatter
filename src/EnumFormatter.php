<?php
namespace Haozu\RequestFormatter;

use Haozu\RequestFormatter\Exception\InternalServerErrorException;
use Haozu\RequestFormatter\Exception\BadRequestException;
/**
 * EnumFormatter 格式化枚举类型
 */
class EnumFormatter extends BaseFormatter implements FormatterInterface 
{   

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
     *          'type'      => 'enum',
     *          'default'   => '',
     *          'range'    => [1,2,3], 
     *        ];
     * 
     */ 
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
        if (!isset($rule['range'])) {
            throw new InternalServerErrorException(sprintf("miss %s's enum range",$this->name));
        }
        if (empty($rule['range']) || !is_array($rule['range'])) {
            throw new InternalServerErrorException(sprintf("%s's enum range can not be empty",$this->name));
        }
        $this->range    = $rule['range'];
    }

    /**
     * 检测枚举类型
     *
     * @return $value
     */
    public function parse() {
        if (!in_array($this->value, $this->range)) {
            throw new BadRequestException(
                sprintf('%s should be in %s, but now %s = %s', 
                    $this->name , implode('/', $this->range), $this->name, $this->value)
            );
        }
        return $this->value;
    }
 
}