<?php

declare(strict_types=1);

$commands = [
    '"php artisan serve"',
    '"php artisan queue:listen --tries=1 --timeout=0"',
];

$names = [
    'server',
    'queue',
];

$colors = [
    '#93c5fd',
    '#c4b5fd',
];

if (function_exists('pcntl_fork')) {
    $commands[] = '"php artisan pail --timeout=0"';
    $names[] = 'logs';
    $colors[] = '#fb7185';
}

$commands[] = '"npm run dev"';
$names[] = 'vite';
$colors[] = '#fdba74';

$command = sprintf(
    'npx concurrently -c "%s" %s --names=%s --kill-others',
    implode(',', $colors),
    implode(' ', $commands),
    implode(',', $names)
);

passthru($command, $exitCode);
exit($exitCode);
