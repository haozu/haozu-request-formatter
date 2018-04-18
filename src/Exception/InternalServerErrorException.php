<?php
namespace Haozu\RequestFormatter\Exception;


/**
 * InternalServerErrorException 服务器运行异常错误
 *
 */

class InternalServerErrorException extends \Exception 
{

    public function __construct($message, $code = 0) 
    {
        parent::__construct(
            sprintf('Interal Server Error: %s', $message), 500 + $code
        );
    }
}
