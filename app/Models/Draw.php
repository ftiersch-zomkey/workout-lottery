<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Draw extends Model
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

    public function exercises() {
        return $this->belongsToMany(\App\Models\Exercise::class, 'draws_exercises')->withPivot('reps');
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'draws_users')->withPivot('succeeded');
    }
}