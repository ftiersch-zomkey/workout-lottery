<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupType extends Model
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
