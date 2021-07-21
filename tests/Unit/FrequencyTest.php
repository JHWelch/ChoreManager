<?php

namespace Tests\Unit;

use App\Enums\Frequency;
use Carbon\Carbon;
use Tests\TestCase;

class FrequencyTest extends TestCase
{
    protected Carbon $today;

    /** @test */
    public function does_not_repeat_frequency_returns_null()
    {
        $frequency = new Frequency(Frequency::DOES_NOT_REPEAT);
        $date      = Carbon::parse('2021-05-01');

        $this->assertNull($frequency->getNextDate($date));
    }

    /** @test */
    public function daily()
    {
        $frequency = new Frequency(Frequency::DAILY);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-05-02',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function weekly()
    {
        $frequency = new Frequency(Frequency::WEEKLY);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-05-08',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function monthly()
    {
        $frequency = new Frequency(Frequency::MONTHLY);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-06-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function quarterly()
    {
        $frequency = new Frequency(Frequency::QUARTERLY);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-08-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function yearly()
    {
        $frequency = new Frequency(Frequency::YEARLY);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2022-05-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function daily_plus_interval()
    {
        $frequency = new Frequency(Frequency::DAILY, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-05-06',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function weekly_plus_interval()
    {
        $frequency = new Frequency(Frequency::WEEKLY, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-06-05',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function monthly_plus_interval()
    {
        $frequency = new Frequency(Frequency::MONTHLY, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-10-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function quarterly_plus_interval()
    {
        $frequency = new Frequency(Frequency::QUARTERLY, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2022-08-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function yearly_plus_interval()
    {
        $frequency = new Frequency(Frequency::YEARLY, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2026-05-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function weekly_on_tuesdays()
    {
        $frequency = new Frequency(Frequency::WEEKLY, 1, Carbon::TUESDAY);
        $date      = Carbon::parse('2021-07-15');

        $this->assertEquals(
            '2021-07-20',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function monthly_on_the_12th()
    {
        $frequency = new Frequency(Frequency::MONTHLY, 1, 12);
        $date      = Carbon::parse('2021-07-12');

        $this->assertEquals(
            '2021-08-12',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function quarterly_on_the_third_day()
    {
        $frequency = new Frequency(Frequency::QUARTERLY, 1, 3);
        $date      = Carbon::parse('2021-07-12');

        $this->assertEquals(
            '2021-10-03',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function yearly_on_the_100th_day()
    {
        $frequency = new Frequency(Frequency::YEARLY, 1, 100);
        $date      = Carbon::parse('2021-07-12');

        $this->assertEquals(
            '2022-04-10',
            $frequency->getNextDate($date)?->toDateString()
        );
    }
}
