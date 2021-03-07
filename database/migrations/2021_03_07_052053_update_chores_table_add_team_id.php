<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChoresTableAddTeamId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chores', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable();

            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('set null');
        });
    }
}
