<?php

namespace Pedreiro\Exceptions;

/**
 * Used when validation fails. Contains the invalid model for easy analysis.
 * Class InvalidModelException
 *
 * @package Pedreiro\Exceptions
 */
class InvalidModelException extends \RuntimeException
{

    /**
     * The invalid model.
     *
     * @var \Support\Models\Ardent
     */
    protected $model;

    /**
     * The message bag instance containing validation error messages
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Receives the invalid model and sets the {@link model} and {@link errors} properties.
     *
     * @param Ardent $model The troublesome model.
     */
    public function __construct(Ardent $model)
    {
        $this->model = $model;
        $this->errors = $model->errors();
    }

    /**
     * Returns the model with invalid attributes.
     *
     * @return \Support\Models\Ardent
     */
    public function getModel(): \Support\Models\Ardent
    {
        return $this->model;
    }

    /**
     * Returns directly the message bag instance with the model's errors.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
