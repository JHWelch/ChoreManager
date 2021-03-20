<?php

namespace Tests\Unit;

use App\Models\Chore;
use Carbon\Carbon;
use Tests\TestCase;

class ChoreInstanceCompleteTest extends TestCase
{
    /** @test */
    public function do_not_repeat_chore_creates_no_instance()
    {
        // Arrange
        // Create Chore with Daily Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => 0,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 day.
        $this->assertEquals(
            null,
            $chore->nextChoreInstance
        );
    }

    /** @test */
    public function daily()
    {
        // Arrange
        // Create Chore with Daily Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => 1,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 day.
        $this->assertEquals(
            today()->addDay()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function weekly()
    {
        // Arrange
        // Create Chore with Daily Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => 2,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 day.
        $this->assertEquals(
            today()->addWeek()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function monthly()
    {
        // Arrange
        // Create Chore with monthly Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 month.
        $this->assertEquals(
            today()->addMonthNoOverflow()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function quarterly()
    {
        // Arrange
        // Create Chore with monthly Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => 4,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 month.
        $this->assertEquals(
            today()->addQuarterNoOverflow()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function yearly()
    {
        // Arrange
        // Create Chore with monthly Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => 5,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 month.
        $this->assertEquals(
            today()->addYearNoOverflow()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function daily_plus_interval()
    {
        // Arrange
        // Create Chores with Daily Frequency every 2 and every 3 days
        $chore1 = Chore::factory()->create([
            'frequency_id'       => 1,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => 1,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addDays(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addDays(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function weekly_plus_interval()
    {
        // Arrange
        // Create Chores with weekly Frequency every 2 and every 3 weeks
        $chore1 = Chore::factory()->create([
            'frequency_id'       => 2,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => 2,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addWeeks(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addWeeks(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function monthly_plus_interval()
    {
        // Arrange
        // Create Chores with monthly Frequency every 2 and every 3 months
        $chore1 = Chore::factory()->create([
            'frequency_id'       => 3,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => 3,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addMonthsNoOverflow(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addMonthsNoOverflow(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function quarterly_plus_interval()
    {
        // Arrange
        // Create Chores with quarterly Frequency every 2 and every 3 months
        $chore1 = Chore::factory()->create([
            'frequency_id'       => 4,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => 4,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addQuartersNoOverflow(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addQuartersNoOverflow(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function yearly_plus_interval()
    {
        // Arrange
        // Create Chores with yearly Frequency every 2 and every 3 months
        $chore1 = Chore::factory()->create([
            'frequency_id'       => 5,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => 5,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addYearsNoOverflow(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addYearsNoOverflow(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }
}
