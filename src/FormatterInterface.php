<?php
namespace Haozu\RequestFormatter;
/**
 * FormatterInterface 格式化接口
 */
interface FormatterInterface 
{	
	/**
     * @param  string $value 变量值
     * @param  array  $rule  规则数组 
     */ 
    public function onInitialize($value,array $rule);

    public function parse();
    
}