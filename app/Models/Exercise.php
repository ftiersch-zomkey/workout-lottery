<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exercise extends Model
{

    protected $table = 'exercises';

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

    public function scopeRandom($query, $number = 1) {
        $query->orderBy(DB::raw('RAND()'))->take($number);
    }
}
