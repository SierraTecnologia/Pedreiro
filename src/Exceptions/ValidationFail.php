<?php

namespace Pedreiro\Exceptions;

use Illuminate\Validation\Validator;

/**
 * Generic exception
 */
class ValidationFail extends Exception
{
    /**
     * @var \Illuminate\Validation\Validator
     */
    public $validation;

    /**
     * @param Validator $validation
     * @param string    $message
     * @param int       $code
     */
    public function __construct(Validator $validation, $message = null, $code = 0)
    {
        $this->validation = $validation;

        parent::__construct('Validation failure', $code);
    }
}
