<?php

namespace App\Models;

class Excercise
{

    protected $table = 'excercises';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    public function groups() {
        return $this->belongsToMany(\App\Models\Group::class, 'groups_excercises')->withPivot('min_reps', 'max_reps');
    }

    public function draws() {
        return $this->belongsToMany(\App\Models\Draw::class, 'groups_draws')->withPivot('reps');
    }
}
