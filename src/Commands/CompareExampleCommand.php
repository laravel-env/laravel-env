<?php

declare(strict_types=1);

namespace LaravelEnv\LaravelEnv\Commands;

use Illuminate\Console\Command;
use LaravelEnv\LaravelEnv\Validator;
use RuntimeException;

class CompareExampleCommand extends Command
{
    protected $signature = 'env:compare';

    protected $description = 'Compare keys between .env and .env.example files';

    public function handle(Validator $validator)
    {
        try {
            $comparison = $validator->compare();

            // Display keys that are only in .env
            if (! empty($comparison['only_in_env'])) {
                $this->warn('Keys only present in .env:');
                foreach ($comparison['only_in_env'] as $key) {
                    $this->line("  - {$key}");
                }
                $this->newLine();
            }

            // Display keys that are only in .env.example
            if (! empty($comparison['only_in_env_example'])) {
                $this->warn('Keys only present in .env.example:');
                foreach ($comparison['only_in_env_example'] as $key) {
                    $this->line("  - {$key}");
                }
                $this->newLine();
            }

            // Display summary
            $this->info('Summary:');
            $this->table(
                ['Location', 'Count'],
                [
                    ['Only in .env', count($comparison['only_in_env'])],
                    ['Only in .env.example', count($comparison['only_in_env_example'])],
                    ['Common to both', count($comparison['common'])],
                    ['Total unique keys', count($comparison['only_in_env']) +
                        count($comparison['only_in_env_example']) +
                        count($comparison['common'])],
                ],
            );

            if (empty($comparison['only_in_env']) && empty($comparison['only_in_env_example'])) {
                $this->info('✓ All keys match between .env and .env.example');

                return Command::SUCCESS;
            }

            return Command::FAILURE;
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
