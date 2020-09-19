<?php

namespace Pedreiro\Exceptions\HttpException;

use Pedreiro\Exceptions\HttpException;

class BadRequestException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 400;

    /**
     * @var string
     */
    protected $statusMessage = 'Bad Request';
}
