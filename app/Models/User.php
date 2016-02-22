<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function groups() {
        return $this->belongsToMany(\App\Models\Group::class, 'groups_users');
    }

    public function draws() {
        return $this->belongsToMany(\App\Models\Draw::class, 'draws_users')->withPivot('succeeded');
    }

    public function scopeRandomWinners($query, $count = 1) {
        $query->orderBy(DB::raw('RAND()'))->take($count);
    }
}
