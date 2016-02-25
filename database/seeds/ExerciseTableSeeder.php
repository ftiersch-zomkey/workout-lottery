<?php

use Illuminate\Database\Seeder;

class ExerciseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = ['Push-Up', 'Pull-Up', 'Squat', 'Sit-Up', 'Crunch'];

        foreach ($exercises as $exercise) {
            if (\App\Models\Exercise::whereName($exercise)->count() == 0) {
                \App\Models\Exercise::create([
                    'name' => $exercise
                ]);
            }
        }
    }
}