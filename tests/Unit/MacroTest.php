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
    public function Collection_nextAfter_gets_the_item_that_comes_after_an_item()
    {
        // Arrange
        // Create collection
        $collection = collect([1, 2, 3, 4]);

        // Act
        // Run macro to get next item
        $item_found = $collection->nextAfter(2);

        // Assert
        // The item after the specified item as found
        $this->assertEquals(3, $item_found);
    }

    /** @test */
    public function Collection_nextAfter_wraps_around_when_the_item_is_last_if_wrap_is_specified()
    {
        // Arrange
        // Create collection
        $collection = collect([1, 2, 3, 4]);

        // Act
        // Run macro to get next item specifying last
        $item_found = $collection->nextAfter(4, false, true);

        // Assert
        // The first item is returned.
        $this->assertEquals(1, $item_found);
    }

    /** @test */
    public function Collection_nextAfter_returns_null_if_wrap_is_not_specified()
    {
        // Arrange
        // Create collection
        $collection = collect([1, 2, 3, 4]);

        // Act
        // Run macro to get next item specifying last
        $item_found = $collection->nextAfter(4);

        // Assert
        // The first item is returned.
        $this->assertEquals(null, $item_found);
    }
}
