<?php

namespace App;

use App\Events\MilestoneAchieved;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $guarded = [];

    public static function achieve(array $attributes) : void
    {
      event(new MilestoneAchieved($attributes));
    }
}
