<?php
/**
 * Database connection (Neon PostgreSQL via PDO)
 * -----------------------------------------------
 * Credentials are read from environment variables only.
 * Never hardcode credentials in this file.
 *
 * Required environment variables:
 *   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD
 *
 * You can set these in your local environment, in an .env file loaded
 * by your web server, or via `putenv()` in a non-committed bootstrap file.
 */

declare(strict_types=1);

/**
 * Minimal .env loader (no external dependency). Only used if the
 * variables aren't already set by the real environment/hosting panel.
 */
function loadEnvFile(string $path): void
{
    if (!is_readable($path)) {
        return;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if ($key !== '' && getenv($key) === false) {
            putenv("{$key}={$value}");
        }
    }
}

loadEnvFile(__DIR__ . '/../.env');

function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: 'ep-nameless-fog-ay4ojcsk-pooler.c-5.us-east-2.aws.neon.tech';
    $port = getenv('DB_PORT') ?: '5432';
    $name = getenv('DB_NAME') ?: 'neondb';
    $user = getenv('DB_USER') ?: 'neondb_owner';
    $pass = getenv('DB_PASSWORD') ?: 'npg_hjN4EuMRY7Hl';

    if ($host === '' || $name === '' || $user === '') {
        http_response_code(500);
        error_log('Database environment variables are not configured.');
        die('Service temporarily unavailable. Please try again later.');
    }

    // Neon requires SSL.
    $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode=require";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log('Database connection failed: ' . $e->getMessage());
        die('Service temporarily unavailable. Please try again later.');
    }

    return $pdo;
}
