<?php

namespace App\Models;

class Draw
{

    protected $table = 'draws';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id'
    ];

    public function group() {
        return $this->belongsTo(\App\Models\Group::class);
    }

    public function excercises() {
        return $this->belongsToMany(\App\Models\Excercise::class, 'draws_excercises')->withPivot('reps');
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'draws_users')->withPivot('succeeded');
    }
}