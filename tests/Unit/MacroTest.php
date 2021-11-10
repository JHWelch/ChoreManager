<?php

use App\Providers\MacroServiceProvider;


beforeEach(function () {
    (new MacroServiceProvider(app()))->boot();
});

test('collection next after gets the item that comes after an item', function () {
    // Arrange
    // Create collection
    $collection = collect([1, 2, 3, 4]);

    // Act
    // Run macro to get next item
    $item_found = $collection->nextAfter(2);

    // Assert
    // The item after the specified item as found
    expect($item_found)->toEqual(3);
});

test('collection next after wraps around when the item is last if wrap is specified', function () {
    // Arrange
    // Create collection
    $collection = collect([1, 2, 3, 4]);

    // Act
    // Run macro to get next item specifying last
    $item_found = $collection->nextAfter(4, false, true);

    // Assert
    // The first item is returned.
    expect($item_found)->toEqual(1);
});

test('collection next after returns null if wrap is not specified', function () {
    // Arrange
    // Create collection
    $collection = collect([1, 2, 3, 4]);

    // Act
    // Run macro to get next item specifying last
    $item_found = $collection->nextAfter(4);

    // Assert
    // The first item is returned.
    expect($item_found)->toEqual(null);
});
