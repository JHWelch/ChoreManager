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
}
