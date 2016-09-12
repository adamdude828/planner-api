<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("recipes", function(Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->unsigned()->index();
            $table->foreign('user_id')->references("id")->on("users");
            $table->text("directions");
            $table->tinyInteger('is_public');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create("user_recipe_schedule", function(Blueprint $table) {
            $table->increments("id");
            $table->date("planned_day");
            $table->integer("recipe_id");
            $table->integer("user_id");
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create("ingredient_recipe", function(Blueprint $table) {
            $table->increments("id");
            $table->integer('recipe_id');
            $table->integer("ingredient_id");
            $table->string("unit");
            $table->string("amount");
        });

        Schema::create("ingredients", function(Blueprint $table) {
            $table->increments("id");
            $table->string("name")->unique();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ingredients');
        Schema::drop("ingredient_recipe");
        Schema::drop("user_recipe_schedule");
        Schema::drop('recipes');
    }
}
