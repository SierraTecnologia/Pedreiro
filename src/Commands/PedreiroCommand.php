<?php

namespace Pedreiro\Commands;

use Illuminate\Console\Command;

class PedreiroCommand extends Command
{
    public $signature = 'pedreiro';

    public $description = 'My command';

    public function handle(): void
    {
        $this->comment('All done');
    }
}
