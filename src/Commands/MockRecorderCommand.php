<?php

namespace Knutle\MockRecorder\Commands;

use Illuminate\Console\Command;

class MockRecorderCommand extends Command
{
    public $signature = 'pest-mock-recorder';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
