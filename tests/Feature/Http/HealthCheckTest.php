<?php

test('health check returns an ok', function () {
    $response = $this->get(route('health-check'));

    $response->assertOk();
});
