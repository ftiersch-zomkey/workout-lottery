<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $appends = ['can_still_succeed'];

    protected $dates = ['created_at', 'updated_at'];

    public function group() {
        return $this->belongsTo(\App\Models\Group::class);
    }

    public function exercises() {
        return $this->belongsToMany(\App\Models\Exercise::class, 'draws_exercises')->withPivot('reps');
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'draws_users')->withPivot('succeeded');
    }

    public function scopeListed($query) {
        $query->with('group', 'exercises', 'users')->orderBy('created_at', 'DESC');
    }

    public function getCanStillSucceedAttribute() {
        return Carbon::now()->diffInMinutes($this->created_at) < $this->group->finish_exercise_time;
    }
}