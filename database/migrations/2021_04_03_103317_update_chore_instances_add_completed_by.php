<?php

use App\Models\ChoreInstance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChoreInstancesAddCompletedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chore_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('completed_by_id')->nullable();

            $table->foreign('completed_by_id')
                ->references('id')
                ->on('users');
        });

        // Set completed user based on user_id
        ChoreInstance::completed()
            ->get()
            ->each(fn ($ci) => $ci->update(['completed_by_id' => $ci->user_id]));
    }
}
