<?php

it('pongs to your ping', function () {
    $this->get(route('ping'))
        ->assertOk()
        ->assertSee('pong');
});
