<?php

use Illuminate\Database\Seeder;
use Mealz\Models\User;

/**
 * Created by PhpStorm.
 * User: aholsinger
 * Date: 9/10/16
 * Time: 1:05 PM
 */

class UserTableSeeder extends Seeder {

    public function run() {
        DB::statement("SET foreign_key_checks=0");
        DB::table('users')->truncate();

        factory(User::class, 10)->create();


    }
}