#!/usr/bin/env php
<?php

use Symfony\Component\Process\Process;

require_once 'vendor/autoload.php';

function checkOutput($returnCode)
{
    if ($returnCode === 0) {
        return;
    }

    echo 'Aborting commit...' . PHP_EOL;
    exit(1);
}

function runCheck($command, $title)
{
    echo "Checking $title... " . PHP_EOL;
    $process = new Process($command, timeout: 300);

    $process->start();

    foreach ($process as $type => $data) {
        echo $data;
    }

    $process->wait();

    checkOutput($process->getExitCode());
}

runCheck(['vendor/bin/phpstan', 'analyse'], 'types');
runCheck(['vendor/bin/pint', '--test'], 'code style');
runCheck(['vendor/bin/phpunit'], 'tests');

exit(0);
