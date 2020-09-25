<?php

namespace Pedreiro\DataCollectors;

use Illuminate\Http\Request;

interface DataCollectorInterface
{
    public function collect(Request $request);
}
