<?php

namespace App\Models;

class GroupType
{

    protected $table = 'group_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function groups() {
        return $this->hasMany(\App\Models\Group::class);
    }
}
