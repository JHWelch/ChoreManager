#!/usr/bin/env php
<?php

function checkOutput($output, $returnCode)
{
    if ($returnCode === 0) {
        return;
    }

    // Show full output
    echo PHP_EOL . implode(PHP_EOL, $output) . PHP_EOL;
    echo 'Aborting commit...' . PHP_EOL;
    exit(1);
}

function runCheck($command, $title)
{
    echo "Checking $title... ";
    exec($command, $output, $returnCode);

    checkOutput($output, $returnCode);

    // Show summary (last line)
    echo array_pop($output) . PHP_EOL;
}

runCheck('vendor/bin/phpunit', 'tests');
runCheck('vendor/bin/pint --test', 'code style');
runCheck('vendor/bin/phpstan analyse', 'types');

exit(0);
