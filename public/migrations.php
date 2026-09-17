<?php

declare(strict_types=1);

const MIGRATION_TOKEN = 'pikefresh-migrate-2026';

header('Content-Type: text/plain; charset=UTF-8');

if (! isset($_GET['token']) || ! hash_equals(MIGRATION_TOKEN, (string) $_GET['token'])) {
    http_response_code(403);
    echo "Forbidden. Missing or invalid token.\n";
    exit;
}

if (isset($_GET['delete']) && $_GET['delete'] === '1') {
    @unlink(__FILE__);
    echo "migrations.php delete command executed. Remove it manually if this file still opens.\n";
    exit;
}

if (! isset($_GET['run']) || $_GET['run'] !== '1') {
    echo "Ready.\n\n";
    echo "Run migrations:\n";
    echo "  /migrations.php?token=" . MIGRATION_TOKEN . "&run=1\n\n";
    echo "Delete this file afterward:\n";
    echo "  /migrations.php?token=" . MIGRATION_TOKEN . "&delete=1\n";
    exit;
}

set_time_limit(300);

$basePath = dirname(__DIR__);
$databaseDirectory = $basePath . '/database';
$databasePath = $databaseDirectory . '/database.sqlite';
$autoload = $basePath . '/vendor/autoload.php';
$bootstrap = $basePath . '/bootstrap/app.php';

if (! is_dir($databaseDirectory) && ! mkdir($databaseDirectory, 0755, true) && ! is_dir($databaseDirectory)) {
    http_response_code(500);
    echo "Could not create database directory.\n";
    exit;
}

if (! file_exists($databasePath) && ! touch($databasePath)) {
    http_response_code(500);
    echo "Could not create SQLite database file.\n";
    exit;
}

if (! file_exists($autoload)) {
    http_response_code(500);
    echo "Missing vendor/autoload.php. Install Composer dependencies first.\n";
    exit;
}

if (! file_exists($bootstrap)) {
    http_response_code(500);
    echo "Missing bootstrap/app.php.\n";
    exit;
}

chdir($basePath);
require $autoload;

$app = require $bootstrap;
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = [
    ['migrate', ['--force' => true]],
    ['optimize:clear', []],
    ['storage:link', []],
];

echo "Starting Laravel migration...\n";
echo "Base path: {$basePath}\n\n";

foreach ($commands as [$command, $parameters]) {
    echo ">>> php artisan {$command}\n";

    try {
        $output = new \Symfony\Component\Console\Output\BufferedOutput;
        $status = $kernel->call($command, $parameters, $output);
        $text = trim($output->fetch());
        echo $text !== '' ? $text . "\n" : "(no output)\n";
        echo "Exit code: {$status}\n\n";

        if ($status !== 0) {
            http_response_code(500);
            echo "Stopped because '{$command}' failed.\n";
            exit;
        }
    } catch (Throwable $exception) {
        http_response_code(500);
        echo "ERROR while running '{$command}':\n";
        echo $exception->getMessage() . "\n";
        echo $exception->getFile() . ':' . $exception->getLine() . "\n";
        exit;
    }
}

echo "Migration completed successfully.\n\n";
echo "IMPORTANT: delete this file now:\n";
echo "/migrations.php?token=" . MIGRATION_TOKEN . "&delete=1\n";
