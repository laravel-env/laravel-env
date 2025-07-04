<?php

declare(strict_types=1);

namespace LaravelEnv\LaravelEnv\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use LaravelEnv\LaravelEnv\Exceptions\InvalidEnvironmentException;

use function Laravel\Prompts\confirm;

class ValidateCommand extends Command
{
    public $signature = 'env:validate';

    public $description = 'Validate the .env file';

    public function handle(): int
    {
        $this->validateCurrentEnv();

        return self::SUCCESS;
    }

    protected function validateCurrentEnv(): void
    {
        $env = [];
        $appEnv = env('APP_ENV', 'development');
        $schema = config('env.schema.'.$appEnv, []);

        if (empty($schema)) {
            $this->error('Error: No env schema defined for the current environment: '.$appEnv);
            $this->info('Please define the env schema in config/env.php, you can read more in the documentation');
            $shouldValidateExample = confirm('Do you want validate your .env against the .env.example file?');
            if ($shouldValidateExample) {
                $this->call(CompareExampleCommand::class);
            }

            return;
        }

        $this->info('Validating your environment');

        foreach (array_keys($schema) as $key) {
            $env[$key] = env($key, '');
        }

        $validator = Validator::make(
            $env,
            $schema,
            [],
            array_combine(array_keys($schema), array_map('strtoupper', array_keys($schema))),
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            if (config('env.config.throw', true)) {
                throw new InvalidEnvironmentException();
            }

            return;
        }

        $this->info('Current environment validated successfully');
    }
}
