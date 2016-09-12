<?php

namespace Mealz\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecipeSchedule extends Model
{
    //
    protected $table = 'user_recipe_schedule';

    public function ingredients() {
        return $this->belongsTo('ingredient');
    }

    public function recipes() {
        return $this->belongsTo('recipe');
    }

    public function users() {
        return $this->belongsTo("user");
    }
}
