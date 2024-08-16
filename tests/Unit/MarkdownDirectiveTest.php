<?php

use Tests\Concerns\TestsBlade;

uses(TestsBlade::class);

test('markdown directive converts markdown to html', function () {
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

    $this->assertDirectiveOutputEquals(
        $expected,
        '@markdown($markdown)',
        ['markdown' => $markdown],
        'Expected Markdown to be converted to HTML'
    );
});

test('markdown directive parses dangerous input', function () {
    $markdown = '<script>alert(\'Gotcha!\')</script>';

    $this->assertDirectiveOutputNotEquals(
        $markdown,
        '@markdown($markdown)',
        ['markdown' => $markdown],
        'Expected dangerous script to be removed.'
    );
});
