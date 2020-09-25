<?php

namespace Pedreiro\Exceptions\Coder;

/**
 * Used when validation fails. Contains the invalid model for easy analysis.
 * Class InvalidModelException
 *
 * @package Pedreiro\Exceptions\Coder
 */
class EloquentTableNotExistException extends EloquentHasErrorException
{
    
    /**
     * @var string
     */
    public $tableName;

    /**
     * @param string  $className
     * @param string  $tableName
     * @param int $code
     */
    public function __construct(string $className, $tableName, $code = 0)
    {
        $this->tableName = $tableName;

        $message = 'Table '.$this->tableName.' not exist in database';

        parent::__construct($className, $message, $code);
    }
}
