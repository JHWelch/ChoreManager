<?php

namespace Tests\Concerns;

trait TestsBlade
{
    private $blade;

    protected function setUpTestsBlade(): void
    {
        $this->blade = resolve('blade.compiler');
    }

    /**
     * Evaluate a Blade expression with the given $variables in scope equals a given string.
     *
     * @param  string  $expected  The expected output.
     * @param  string  $expression  The Blade directive, as it would be written in a view.
     * @param  array  $variables  Variables to extract() into the scope of the eval() statement.
     * @param  string  $message  A message to display if the output does not match $expected.
     */
    protected function assertDirectiveOutputEquals(
        string $expected,
        string $expression = '',
        array $variables = [],
        string $message = ''
    ) {
        $this->assertDirectiveOutput(true, $expected, $expression, $variables, $message);
    }

    /**
     * Evaluate a Blade expression with the given $variables in scope does not equal a given string.
     *
     * @param  string  $expected  The expected output.
     * @param  string  $expression  The Blade directive, as it would be written in a view.
     * @param  array  $variables  Variables to extract() into the scope of the eval() statement.
     * @param  string  $message  A message to display if the output does not match $expected.
     */
    protected function assertDirectiveOutputNotEquals(
        string $expected,
        string $expression = '',
        array $variables = [],
        string $message = ''
    ) {
        $this->assertDirectiveOutput(false, $expected, $expression, $variables, $message);
    }

    /**
     * Evaluate a Blade expression with the given $variables in scope.
     *
     * @param  string  $expected  The expected output.
     * @param  string  $expression  The Blade directive, as it would be written in a view.
     * @param  array  $variables  Variables to extract() into the scope of the eval() statement.
     * @param  string  $message  A message to display if the output does not match $expected.
     */
    private function assertDirectiveOutput(
        bool $equals,
        string $expected,
        string $expression = '',
        array $variables = [],
        string $message = ''
    ) {
        $compiled = $this->blade->compileString($expression);
        ob_start();
        extract($variables);
        eval(' ?>' . $compiled . '<?php ');
        $output = ob_get_clean();

        $equals
            ? $this->assertEquals($expected, trim($output), $message)
            : $this->assertNotEquals($expected, trim($output), $message);
    }
}
