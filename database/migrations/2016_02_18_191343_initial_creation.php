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

            $table->smallInteger('interval_minutes', false, true);

            $table->nullableTimestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

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
