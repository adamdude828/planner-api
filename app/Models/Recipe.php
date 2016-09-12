<?php

namespace Mealz;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    //
    protected $table = 'recipes';

    public function users() {
        return $this->belongsToMany('Mealz\Models\User');
    }
}
