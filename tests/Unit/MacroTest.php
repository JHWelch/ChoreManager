<?php

use App\Providers\MacroServiceProvider;

beforeEach(function () {
    (new MacroServiceProvider(app()))->boot();
});

test('collection next after gets the item that comes after an item', function () {
    $collection = collect([1, 2, 3, 4]);

    $item_found = $collection->nextAfter(2);

    expect($item_found)->toEqual(3);
});

test('collection next after wraps around when the item is last if wrap is specified', function () {
    $collection = collect([1, 2, 3, 4]);

    $item_found = $collection->nextAfter(4, false, true);

    expect($item_found)->toEqual(1);
});

test('collection next after returns null if wrap is not specified', function () {
    $collection = collect([1, 2, 3, 4]);

    $item_found = $collection->nextAfter(4);

    expect($item_found)->toEqual(null);
});
