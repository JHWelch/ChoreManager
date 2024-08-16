<?php

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use Carbon\Carbon;

test('does not repeat frequency returns null', function () {
    $frequency = new Frequency(FrequencyType::doesNotRepeat);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date))->toBeNull();
});

test('daily', function () {
    $frequency = new Frequency(FrequencyType::daily);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-05-02');
});

test('weekly', function () {
    $frequency = new Frequency(FrequencyType::weekly);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-05-08');
});

test('monthly', function () {
    $frequency = new Frequency(FrequencyType::monthly);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-06-01');
});

test('quarterly', function () {
    $frequency = new Frequency(FrequencyType::quarterly);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-08-01');
});

test('yearly', function () {
    $frequency = new Frequency(FrequencyType::yearly);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2022-05-01');
});

test('daily plus interval', function () {
    $frequency = new Frequency(FrequencyType::daily, 5);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-05-06');
});

test('weekly plus interval', function () {
    $frequency = new Frequency(FrequencyType::weekly, 5);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-06-05');
});

test('monthly plus interval', function () {
    $frequency = new Frequency(FrequencyType::monthly, 5);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-10-01');
});

test('quarterly plus interval', function () {
    $frequency = new Frequency(FrequencyType::quarterly, 5);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2022-08-01');
});

test('yearly plus interval', function () {
    $frequency = new Frequency(FrequencyType::yearly, 5);
    $date = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2026-05-01');
});

test('weekly on tuesdays', function () {
    $frequency = new Frequency(FrequencyType::weekly, 1, Carbon::TUESDAY);
    $date = Carbon::parse('2021-07-15');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-07-20');
});

test('monthly on the 12th', function () {
    $frequency = new Frequency(FrequencyType::monthly, 1, 12);
    $date = Carbon::parse('2021-07-12');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-08-12');
});

test('quarterly on the third day', function () {
    $frequency = new Frequency(FrequencyType::quarterly, 1, 3);
    $date = Carbon::parse('2021-07-12');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2021-10-03');
});

test('yearly on the 100th day', function () {
    $frequency = new Frequency(FrequencyType::yearly, 1, 100);
    $date = Carbon::parse('2021-07-12');

    expect($frequency->getNextDate($date)?->toDateString())->toEqual('2022-04-10');
});
