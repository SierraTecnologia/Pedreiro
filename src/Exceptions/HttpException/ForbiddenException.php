<?php

namespace Pedreiro\Exceptions\HttpException;

use Pedreiro\Exceptions\HttpException;

class ForbiddenException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 403;

    /**
     * @var string
     */
    protected $statusMessage = 'Forbidden';
}
