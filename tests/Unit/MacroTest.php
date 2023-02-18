<?php

namespace Tests\Unit;

use App\Providers\MacroServiceProvider;
use PHPUnit\Framework\TestCase;

class MacroTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new MacroServiceProvider(app()))->boot();
    }

    /** @test */
    public function Collection_nextAfter_gets_the_item_that_comes_after_an_item(): void
    {
        $collection = collect([1, 2, 3, 4]);

        $item_found = $collection->nextAfter(2);

        $this->assertEquals(3, $item_found);
    }

    /** @test */
    public function Collection_nextAfter_wraps_around_when_the_item_is_last_if_wrap_is_specified(): void
    {
        $collection = collect([1, 2, 3, 4]);

        $item_found = $collection->nextAfter(4, false, true);

        $this->assertEquals(1, $item_found);
    }

    /** @test */
    public function Collection_nextAfter_returns_null_if_wrap_is_not_specified(): void
    {
        $collection = collect([1, 2, 3, 4]);

        $item_found = $collection->nextAfter(4);

        $this->assertEquals(null, $item_found);
    }
}
