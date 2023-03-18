<?php

namespace Tests\Unit;

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use Carbon\Carbon;
use Tests\TestCase;

class FrequencyTest extends TestCase
{
    protected Carbon $today;

    /** @test */
    public function does_not_repeat_frequency_returns_null(): void
    {
        $frequency = new Frequency(FrequencyType::doesNotRepeat);
        $date      = Carbon::parse('2021-05-01');

        $this->assertNull($frequency->getNextDate($date));
    }

    /** @test */
    public function daily(): void
    {
        $frequency = new Frequency(FrequencyType::daily);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-05-02',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function weekly(): void
    {
        $frequency = new Frequency(FrequencyType::weekly);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-05-08',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function monthly(): void
    {
        $frequency = new Frequency(FrequencyType::monthly);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-06-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function quarterly(): void
    {
        $frequency = new Frequency(FrequencyType::quarterly);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-08-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function yearly(): void
    {
        $frequency = new Frequency(FrequencyType::yearly);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2022-05-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function daily_plus_interval(): void
    {
        $frequency = new Frequency(FrequencyType::daily, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-05-06',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function weekly_plus_interval(): void
    {
        $frequency = new Frequency(FrequencyType::weekly, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-06-05',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function monthly_plus_interval(): void
    {
        $frequency = new Frequency(FrequencyType::monthly, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2021-10-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function quarterly_plus_interval(): void
    {
        $frequency = new Frequency(FrequencyType::quarterly, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2022-08-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function yearly_plus_interval(): void
    {
        $frequency = new Frequency(FrequencyType::yearly, 5);
        $date      = Carbon::parse('2021-05-01');

        $this->assertEquals(
            '2026-05-01',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function weekly_on_tuesdays(): void
    {
        $frequency = new Frequency(FrequencyType::weekly, 1, Carbon::TUESDAY);
        $date      = Carbon::parse('2021-07-15');

        $this->assertEquals(
            '2021-07-20',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function monthly_on_the_12th(): void
    {
        $frequency = new Frequency(FrequencyType::monthly, 1, 12);
        $date      = Carbon::parse('2021-07-12');

        $this->assertEquals(
            '2021-08-12',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function quarterly_on_the_third_day(): void
    {
        $frequency = new Frequency(FrequencyType::quarterly, 1, 3);
        $date      = Carbon::parse('2021-07-12');

        $this->assertEquals(
            '2021-10-03',
            $frequency->getNextDate($date)?->toDateString()
        );
    }

    /** @test */
    public function yearly_on_the_100th_day(): void
    {
        $frequency = new Frequency(FrequencyType::yearly, 1, 100);
        $date      = Carbon::parse('2021-07-12');

        $this->assertEquals(
            '2022-04-10',
            $frequency->getNextDate($date)?->toDateString()
        );
    }
}
