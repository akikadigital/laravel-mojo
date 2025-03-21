<?php

namespace Akika\Mojo\Commands;

use Illuminate\Console\Command;

class MojoCommand extends Command
{
    public $signature = 'laravel-mojo';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
