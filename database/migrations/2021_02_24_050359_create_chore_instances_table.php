<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoreInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chore_instances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chore_id');
            $table->date('due_date');
            $table->date('completed_date')->nullable();
            $table->timestamps();

            $table->foreign('chore_id')
                ->references('id')
                ->on('chores')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chore_instance');
    }
}
