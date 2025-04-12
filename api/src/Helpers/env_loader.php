<?php

/**
 * @param string $envFilePath
 * @return void
 */
function loadEnv(string $envFilePath): void
{
    if (!file_exists($envFilePath)) {
        throw new RuntimeException("Environment file not found at path: $envFilePath");
    }

    $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $name = trim($parts[0]);
        $value = trim($parts[1]);

        if (!getenv($name)) {
            putenv("$name=$value");
        }
    }
}

loadEnv(__DIR__ . '/../../config/.env.dev');
