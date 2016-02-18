<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialCreation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->smallInteger('interval_minutes', false, true)->default(30);
            $table->time('interval_time_start', false, true)->default('10:00:00');
            $table->time('interval_time_end', false, true)->default('20:00:00');

            $table->smallInteger('number_of_winners', false, true)->default(1);
            $table->tinyInteger('number_of_winners_is_percent', false, true)->default(0);

            $table->smallInteger('finish_excercise_time')->default(25);

            $table->nullableTimestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('description')->nullable();

            $table->nullableTimestamps();
        });

        Schema::create('groups_users', function (Blueprint $table) {
            $table->integer('group_id', false, true);
            $table->integer('user_id', false, true);

            $table->nullableTimestamps();

            $table->primary(['group_id', 'user_id']);

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('groups_exercises', function (Blueprint $table) {
            $table->integer('group_id', false, true);
            $table->integer('user_id', false, true);

            $table->integer('min_reps', false, true)->default(1);
            $table->integer('max_reps', false, true)->default(1);

            $table->nullableTimestamps();

            $table->primary(['group_id', 'user_id']);

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('draws', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_id', false, true);

            $table->nullableTimestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });

        Schema::create('draws_users', function (Blueprint $table) {
            $table->integer('draw_id', false, true);
            $table->integer('user_id', false, true);

            $table->tinyInteger('succeeded', false, true)->default(0);

            $table->nullableTimestamps();

            $table->primary(['draw_id', 'user_id']);

            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
