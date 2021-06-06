<?php

namespace Pedreiro\Facades;

use Illuminate\Support\Facades\Facade;

class PedreiroURL extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pedreiro.url';
    }
}
