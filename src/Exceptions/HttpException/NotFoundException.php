<?php

namespace Pedreiro\Exceptions\HttpException;

use Pedreiro\Exceptions\HttpException;

class NotFoundException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 404;

    /**
     * @var string
     */
    protected $statusMessage = 'Not Found';
}
