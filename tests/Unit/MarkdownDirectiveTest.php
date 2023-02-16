<?php

namespace Tests\Unit;

use Tests\BladeTestCase;

class MarkdownDirectiveTest extends BladeTestCase
{
    /** @test */
    public function markdown_directive_converts_markdown_to_html(): void
    {
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
    }

    /** @test */
    public function markdown_directive_parses_dangerous_input(): void
    {
        $markdown = '<script>alert(\'Gotcha!\')</script>';

        $this->assertDirectiveOutputNotEquals(
            $markdown,
            '@markdown($markdown)',
            ['markdown' => $markdown],
            'Expected dangerous script to be removed.'
        );
    }
}
