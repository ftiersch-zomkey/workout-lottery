<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{

    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_user_id', 'group_type_id', 'name', 'interval_minutes', 'interval_time_start', 'interval_time_end', 'number_of_winners', 'finish_exercise_time'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function group_type() {
        return $this->belongsTo(\App\Models\GroupType::class, 'group_type_id');
    }

    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'groups_users');
    }

    public function exercises() {
        return $this->belongsToMany(\App\Models\Exercise::class, 'groups_exercises')->withPivot('min_reps', 'max_reps');
    }

    public function draws() {
        return $this->hasMany(\App\Models\Draw::class)->listed();
    }

    /**
     * Standard parameters for a query that lists groups (like "My groups")
     *
     * @param $query
     */
    public function scopeListed($query) {
        $query->with('group_type')->orderBy('name', 'ASC');
    }

    public static function boot() {
        parent::boot();

        self::creating(function ($group) {
            // automatically add the current user as a creator (if it's not overwritten by something)
            if (empty($group->creator_user_id)) {
                if (Auth::check()) {
                    $group->creator_user_id = Auth::user()->id;
                } else {
                    return false;
                }
            }

            // if no group type is set we just assume default
            if (empty($group->group_type_id)) {
                $groupType = GroupType::select('id')->whereName('default')->first();
                $group->group_type_id = $groupType->id;
            }
        });

        self::created(function ($group) {
            // automatically add the creator to the group
            $group->users()->attach($group->creator);
        });
    }

    public function drawWinners() {
        // randomly select the winners for this draw
        $winners = $this->users()->randomWinners($this->number_of_winners)->get();
        if ($winners->isEmpty()) {
            throw new \Exception('No winner found');
        }

        // randomly select the exercises (including reps) for this draw
        $exercises = $this->excercises()->random()->get();
        if ($exercises->isEmpty()) {
            throw new \Exception('No exercise found');
        }

        // create a new draw in the database and attach winners and exercises
        $draw = new Draw([
            'group_id' => $this->id
        ]);
        $draw->users()->sync($winners->pluck('id'));

        foreach ($exercises as $exercise) {
            $draw->exercises()->attach($exercise, ['reps' => rand($exercise->min_reps, $exercise->max_reps)]);
        }
    }
}
