<?php

namespace Pedreiro;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pedreiro\Pedreiro
 */
class PedreiroFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pedreiro';
    }
}
