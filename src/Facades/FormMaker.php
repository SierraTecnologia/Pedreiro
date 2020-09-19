<?php

namespace Pedreiro\Facades;

use Illuminate\Support\Facades\Facade;

class FormMaker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'form-maker';
    }
}
