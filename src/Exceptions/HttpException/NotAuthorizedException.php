<?php

namespace Pedreiro\Exceptions\HttpException;

use Pedreiro\Exceptions\HttpException;

class NotAuthorizedException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 401;

    /**
     * @var string
     */
    protected $statusMessage = 'Not Authorized';
}
