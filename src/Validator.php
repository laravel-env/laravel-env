<?php

namespace LaravelEnv\LaravelEnv;

use RuntimeException;
use Illuminate\Support\Str;

class Validator
{
    /**
     * The required environment keys.
     *
     * @var array
     */
    protected array $requiredKeys = [];

    /**
     * The validation schemas.
     *
     * @var array
     */
    protected array $schemas = [];

    /**
     * The missing environment keys.
     *
     * @var array
     */
    protected array $missingKeys = [];

    /**
     * The invalid environment values.
     *
     * @var array
     */
    protected array $invalidValues = [];

    /**
     * Define a validation schema for an environment key.
     *
     * @param  string  $key
     * @param  string  $type
     * @param  bool  $required
     * @return $this
     */
    public function define(string $key, string $type, bool $required = true): self
    {
        $this->schemas[$key] = compact('type', 'required');

        if ($required) {
            $this->requiredKeys[] = $key;
        }

        return $this;
    }

    /**
     * Validate the environment configuration.
     *
     * @return array
     */
    public function validate(): array
    {
        $this->validateRequiredKeys();
        $this->validateValueTypes();
        $this->validateAgainstExample();

        return [
            'missing_keys' => $this->missingKeys,
            'invalid_values' => $this->invalidValues,
            'is_valid' => $this->isValid(),
        ];
    }

    /**
     * Compare keys between .env and .env.example files.
     *
     * @return array
     */
    public function compare(): array
    {
        $envExample = $this->loadEnvironmentFile('.env.example');
        $env = $this->loadEnvironmentFile('.env');

        return [
            'only_in_env' => array_diff(array_keys($env), array_keys($envExample)),
            'only_in_env_example' => array_diff(array_keys($envExample), array_keys($env)),
            'common' => array_intersect(array_keys($env), array_keys($envExample)),
        ];
    }

    /**
     * Validate that all required keys are present.
     *
     * @return void
     */
    protected function validateRequiredKeys(): void
    {
        $env = $this->loadEnvironmentFile('.env');

        foreach ($this->requiredKeys as $key) {
            if (! isset($env[$key])) {
                $this->missingKeys[] = $key;
            }
        }
    }

    /**
     * Validate the type of each environment value.
     *
     * @return void
     */
    protected function validateValueTypes(): void
    {
        $env = $this->loadEnvironmentFile('.env');

        foreach ($env as $key => $value) {
            if (isset($this->schemas[$key]) && ! $this->isValidType($value, $this->schemas[$key]['type'])) {
                $this->invalidValues[$key] = [
                    'value' => $value,
                    'expected_type' => $this->schemas[$key]['type'],
                ];
            }
        }
    }

    /**
     * Validate against the example environment file.
     *
     * @return void
     */
    protected function validateAgainstExample(): void
    {
        $envExample = $this->loadEnvironmentFile('.env.example');
        $env = $this->loadEnvironmentFile('.env');

        foreach ($envExample as $key => $value) {
            if (! isset($env[$key]) && ! in_array($key, $this->missingKeys)) {
                $this->missingKeys[] = $key;
            }
        }
    }

    /**
     * Load and parse an environment file.
     *
     * @param  string  $filename
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function loadEnvironmentFile(string $filename): array
    {
        $path = base_path($filename);

        if (! file_exists($path)) {
            throw new RuntimeException("Environment file {$path} does not exist.");
        }

        return $this->parseEnvironmentFile($path);
    }

    /**
     * Parse the environment file into an array.
     *
     * @param  string  $path
     * @return array
     */
    protected function parseEnvironmentFile(string $path): array
    {
        $env = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if ($this->isCommentLine($line)) {
                continue;
            }

            if ($this->isValidKeyValuePair($line)) {
                [$key, $value] = $this->parseKeyValuePair($line);
                $env[$key] = $value;
            }
        }

        return $env;
    }

    /**
     * Check if the line is a comment.
     *
     * @param  string  $line
     * @return bool
     */
    protected function isCommentLine(string $line): bool
    {
        return Str::startsWith(trim($line), '#');
    }

    /**
     * Check if the line contains a valid key-value pair.
     *
     * @param  string  $line
     * @return bool
     */
    protected function isValidKeyValuePair(string $line): bool
    {
        return str_contains($line, '=');
    }

    /**
     * Parse a key-value pair from a line.
     *
     * @param  string  $line
     * @return array
     */
    protected function parseKeyValuePair(string $line): array
    {
        [$key, $value] = explode('=', $line, 2);

        return [
            trim($key),
            trim($value, " \t\n\r\0\x0B\"'"),
        ];
    }

    /**
     * Check if a value matches the expected type.
     *
     * @param  mixed  $value
     * @param  string  $type
     * @return bool
     */
    protected function isValidType(mixed $value, string $type): bool
    {
        return match ($type) {
            'string' => is_string($value),
            'number' => is_numeric($value),
            'boolean' => $this->isBoolean($value),
            'url' => $this->isUrl($value),
            'email' => $this->isEmail($value),
            default => true,
        };
    }

    /**
     * Check if a value is a boolean.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isBoolean(mixed $value): bool
    {
        return in_array(Str::lower($value), ['true', 'false', '1', '0']);
    }

    /**
     * Check if a value is a valid URL.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isUrl(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Check if a value is a valid email.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isEmail(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if the validation passed.
     *
     * @return bool
     */
    protected function isValid(): bool
    {
        return empty($this->missingKeys) && empty($this->invalidValues);
    }
}
