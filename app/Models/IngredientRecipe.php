<?php

namespace Mealz\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientRecipe extends Model
{
    //
    protected $table = 'ingredient_recipe';

    public function ingredients() {
        return $this->belongsTo('ingredient');
    }

    public function recipes() {
        return $this->belongsTo('recipe');
    }
}
