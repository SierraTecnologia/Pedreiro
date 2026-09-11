<?php

namespace Pedreiro\Facades;

use Illuminate\Support\Facades\Facade;

class Pedreiro extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pedreiro';
    }
}
