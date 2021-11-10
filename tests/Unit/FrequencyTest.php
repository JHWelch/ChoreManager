<?php

use App\Enums\Frequency;
use Carbon\Carbon;
use Tests\TestCase;

uses(TestCase::class);

test('does not repeat frequency returns null', function () {
    $frequency = new Frequency(Frequency::DOES_NOT_REPEAT);
    $date      = Carbon::parse('2021-05-01');

    expect($frequency->getNextDate($date))->toBeNull();
});

test('daily', function () {
    $frequency = new Frequency(Frequency::DAILY);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-05-02',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('weekly', function () {
    $frequency = new Frequency(Frequency::WEEKLY);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-05-08',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('monthly', function () {
    $frequency = new Frequency(Frequency::MONTHLY);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-06-01',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('quarterly', function () {
    $frequency = new Frequency(Frequency::QUARTERLY);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-08-01',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('yearly', function () {
    $frequency = new Frequency(Frequency::YEARLY);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2022-05-01',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('daily plus interval', function () {
    $frequency = new Frequency(Frequency::DAILY, 5);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-05-06',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('weekly plus interval', function () {
    $frequency = new Frequency(Frequency::WEEKLY, 5);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-06-05',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('monthly plus interval', function () {
    $frequency = new Frequency(Frequency::MONTHLY, 5);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2021-10-01',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('quarterly plus interval', function () {
    $frequency = new Frequency(Frequency::QUARTERLY, 5);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2022-08-01',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('yearly plus interval', function () {
    $frequency = new Frequency(Frequency::YEARLY, 5);
    $date      = Carbon::parse('2021-05-01');

    $this->assertEquals(
        '2026-05-01',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('weekly on tuesdays', function () {
    $frequency = new Frequency(Frequency::WEEKLY, 1, Carbon::TUESDAY);
    $date      = Carbon::parse('2021-07-15');

    $this->assertEquals(
        '2021-07-20',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('monthly on the 12th', function () {
    $frequency = new Frequency(Frequency::MONTHLY, 1, 12);
    $date      = Carbon::parse('2021-07-12');

    $this->assertEquals(
        '2021-08-12',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('quarterly on the third day', function () {
    $frequency = new Frequency(Frequency::QUARTERLY, 1, 3);
    $date      = Carbon::parse('2021-07-12');

    $this->assertEquals(
        '2021-10-03',
        $frequency->getNextDate($date)?->toDateString()
    );
});

test('yearly on the 100th day', function () {
    $frequency = new Frequency(Frequency::YEARLY, 1, 100);
    $date      = Carbon::parse('2021-07-12');

    $this->assertEquals(
        '2022-04-10',
        $frequency->getNextDate($date)?->toDateString()
    );
});
