<?php

namespace LaravelEnv\LaravelEnv\Commands;

use Illuminate\Console\Command;

class EnvCommand extends Command
{
    public $signature = 'env:validate';

    public $description = 'Validate the .env file';

    public function handle(): int
    {
        $this->info('Validating the .env file');

        return self::SUCCESS;
    }
}
