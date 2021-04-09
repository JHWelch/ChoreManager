#!/usr/bin/env php
<?php

echo 'Running tests.. ';
exec('vendor/bin/phpunit', $output, $returnCode);

if ($returnCode !== 0) {
    // Show full output
    echo PHP_EOL . implode(PHP_EOL, $output) . PHP_EOL;
    echo 'Aborting commit..' . PHP_EOL;
    exit(1);
}

// Show summary (last line)
echo array_pop($output) . PHP_EOL;

exit(0);
