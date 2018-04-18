<?php
namespace Haozu\RequestFormatter\Exception;


/**
 * BadRequestException 客户端非法请求
 *
 * 客户端非法请求
 *
 */

class BadRequestException extends \Exception 
{
    public function __construct($message, $code = 0) 
    {
        parent::__construct(
            sprintf('Bad Request: %s',  $message), 400 + $code
        );
    }
}
