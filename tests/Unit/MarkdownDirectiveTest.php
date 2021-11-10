<?php

use Illuminate\Support\Facades\Route;
use Tests\BladeTestCase;

uses(BladeTestCase::class);

test('markdown directive converts markdown to html', function () {
    // Arrange
    // Markdown string and mock component
    $markdown = <<<'EOD'
    # This is a markdown string
    It is rather important
    ## It can have lists
    * Lists
    * With
    * Items
    EOD;

    $expected = <<<'EOD'
    <h1>This is a markdown string</h1>
    <p>It is rather important</p>
    <h2>It can have lists</h2>
    <ul>
    <li>Lists</li>
    <li>With</li>
    <li>Items</li>
    </ul>
    EOD;

    // Assert
    // Markdown directive converts to HTML.
    $this->assertDirectiveOutputEquals(
        $expected,
        '@markdown($markdown)',
        ['markdown' => $markdown],
        'Expected Markdown to be converted to HTML'
    );
});

test('markdown directive parses dangerous input', function () {
    // Arrange
    // Markdown with script and not wanted script output
    $markdown = '<script>alert(\'Gotcha!\')</script>';

    // Assert
    // Markdown is santizied and script is not output directly.
    $this->assertDirectiveOutputNotEquals(
        $markdown,
        '@markdown($markdown)',
        ['markdown' => $markdown],
        'Expected dangerous script to be removed.'
    );
});
