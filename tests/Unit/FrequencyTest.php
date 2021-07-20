<?php

namespace Tests\Unit;

use App\Enums\Frequency;
use Carbon\Carbon;
use Tests\TestCase;

class FrequencyTest extends TestCase
{
    protected Carbon $today;

    public function setUp(): void
    {
        parent::setUp();
        $this->today = today();
    }

    /** @test */
    public function does_not_repeat_frequency_returns_null()
    {
        $frequency = new Frequency(Frequency::DOES_NOT_REPEAT);

        $this->assertNull($frequency->getNextDate($this->today));
    }

    /** @test */
    public function daily()
    {
        $frequency = new Frequency(Frequency::DAILY);

        $this->assertEquals(
            $this->today->addDay()->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function weekly()
    {
        $frequency = new Frequency(Frequency::WEEKLY);

        $this->assertEquals(
            $this->today->addWeek()->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function monthly()
    {
        $frequency = new Frequency(Frequency::MONTHLY);

        $this->assertEquals(
            $this->today->addMonthNoOverflow()->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function quarterly()
    {
        $frequency = new Frequency(Frequency::QUARTERLY);

        $this->assertEquals(
            $this->today->addQuarterNoOverflow()->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function yearly()
    {
        $frequency = new Frequency(Frequency::YEARLY);

        $this->assertEquals(
            $this->today->addYearNoOverflow()->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function daily_plus_interval()
    {
        $frequency = new Frequency(Frequency::DAILY, 5);

        $this->assertEquals(
            $this->today->addDays(5)->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function weekly_plus_interval()
    {
        $frequency = new Frequency(Frequency::WEEKLY, 5);

        $this->assertEquals(
            $this->today->addWeeks(5)->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function monthly_plus_interval()
    {
        $frequency = new Frequency(Frequency::MONTHLY, 5);

        $this->assertEquals(
            $this->today->addMonthsNoOverflow(5)->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function quarterly_plus_interval()
    {
        $frequency = new Frequency(Frequency::QUARTERLY, 5);

        $this->assertEquals(
            $this->today->addQuartersNoOverflow(5)->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }

    /** @test */
    public function yearly_plus_interval()
    {
        $frequency = new Frequency(Frequency::YEARLY, 5);

        $this->assertEquals(
            $this->today->addYearNoOverflow(5)->toDateString(),
            $frequency->getNextDate($this->today)?->toDateString()
        );
    }
}
