<?php

namespace Pedreiro\Exceptions\Coder;

use Support\Components\Database\Render\EloquentRender;

/**
 * Used when validation fails. Contains the invalid model for easy analysis.
 * Class InvalidModelException
 *
 * @package Pedreiro\Exceptions\Coder
 */
class EloquentEntityFailedException extends CoderException
{
    
    /**
     * @var EloquentRender
     */
    public $eloquentRender;
    /**
     * @var array
     */
    public $eloquentRenderErrors;

    /**
     * @param EloquentRender $eloquentRender
     * @param string         $message
     * @param int        $code
     */
    public function __construct(EloquentRender $eloquentRender)
    {
        $this->eloquentRender = $eloquentRender;
        $this->eloquentRenderErrors = $eloquentRender->getErrors();

        $message = 'Eloquent '.$eloquentRender->getModelClass().
        ' hasErrors '.implode('; ', $this->eloquentRenderErrors);

        parent::__construct($message);
    }
}
