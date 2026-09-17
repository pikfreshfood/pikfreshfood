<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Temporary Laravel Server Fix Script
|--------------------------------------------------------------------------
| Upload this file to your Laravel public folder, open it in the browser with
| the token below, then delete it immediately after the fix is complete.
|
| Example:
| https://pikfreshfood.com/server-fix.php?token=pikefresh-fix-2026&run=1
*/

const FIX_TOKEN = 'pikefresh-fix-2026';

header('Content-Type: text/plain; charset=UTF-8');

if (! isset($_GET['token']) || ! hash_equals(FIX_TOKEN, (string) $_GET['token'])) {
    http_response_code(403);
    echo "Forbidden. Missing or invalid token.\n";
    exit;
}

if (isset($_GET['delete']) && $_GET['delete'] === '1') {
    @unlink(__FILE__);
    echo "server-fix.php delete command executed. If this file still opens, remove it manually.\n";
    exit;
}

if (! isset($_GET['run']) || $_GET['run'] !== '1') {
    echo "Ready.\n\n";
    echo "Run fix:\n";
    echo "  /server-fix.php?token=" . FIX_TOKEN . "&run=1\n\n";
    echo "Delete this file after success:\n";
    echo "  /server-fix.php?token=" . FIX_TOKEN . "&delete=1\n";
    exit;
}

set_time_limit(300);

$basePath = dirname(__DIR__);
$autoload = $basePath . '/vendor/autoload.php';
$bootstrap = $basePath . '/bootstrap/app.php';

if (! file_exists($autoload)) {
    http_response_code(500);
    echo "Missing vendor/autoload.php\n";
    echo "Run this on the server first: composer install --no-dev --optimize-autoloader\n";
    exit;
}

if (! file_exists($bootstrap)) {
    http_response_code(500);
    echo "Missing bootstrap/app.php\n";
    exit;
}

chdir($basePath);

require $autoload;

/** @var \Illuminate\Foundation\Application $app */
$app = require $bootstrap;

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = [
    ['optimize:clear', []],
    ['config:clear', []],
    ['cache:clear', []],
    ['route:clear', []],
    ['view:clear', []],
    ['migrate', ['--force' => true]],
    ['storage:link', []],
];

echo "Starting Laravel server fix...\n";
echo "Base path: {$basePath}\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

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

echo "Done. Clear and migrate completed successfully.\n\n";
echo "IMPORTANT: delete this file now:\n";
echo "/server-fix.php?token=" . FIX_TOKEN . "&delete=1\n";

