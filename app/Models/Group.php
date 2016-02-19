<?php

namespace App\Models;

class Group
{

    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_type_id', 'name', 'interval_minutes', 'interval_time_start', 'interval_time_end', 'number_of_winners', 'number_of_winners_is_percent', 'finish_exercise_time'
    ];

    public function group_type() {
        return $this->belongsTo(\App\Models\GroupType::class, 'group_type_id');
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User:class, 'groups_users');
    }

    public function excercises() {
        return $this->belongsToMany(\App\Models\Excercise::class, 'groups_excercises')->withPivot('min_reps', 'max_reps');
    }

    public function draws() {
        return $this->hasMany(\App\Models\Draw:class)->orderBy('created_at', 'DESC');
    }
}
