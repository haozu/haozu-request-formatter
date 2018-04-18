<?php
namespace Haozu\RequestFormatter;

use Haozu\RequestFormatter\Exception\InternalServerErrorException;

/**
 * CallableFormatter 格式化回调类型
 */
class CallableFormatter extends BaseFormatter implements FormatterInterface 
{   

    /*
     * 回调
     */
    protected $callback;

    /*
     * 参数
     */
    protected $args;

    /**
     * 初始化
     *
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     *       [
     *          'name'      => '', 
     *          'type'      => 'boolean',
     *          'callback'  => ['MyClass','method'],  or 'callable'  => 'MyClass::method',    
     *          'default'   => '',
     *          'params'    => '',
     *        ];
     * 
     */
    public function onInitialize($value,array $rule)
    {
        parent::onInitialize($value,$rule);
        $this->args     = [ $value , $rule , isset($rule['params']) ? $rule['params'] : [] ];
        $this->callback = isset($rule['callback']) ? $rule['callback']: (isset($rule['callable']) ? $rule['callable'] : NULL);
    }

    /**
     * 对回调类型进行格式化
     *
     * @return boolean/string 格式化后的变量
     *
     */
    public function parse() {
        
        if (empty($this->callback) || !is_callable($this->callback)) {
            throw new InternalServerErrorException(
                sprintf('invalid callback for rule: %s', $this->name)
            );
        }
        return call_user_func_array($this->callback,$this->args);
    }
}