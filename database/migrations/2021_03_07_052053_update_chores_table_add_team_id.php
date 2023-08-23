<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chores', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable();

            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('set null');
        });
    }
};
