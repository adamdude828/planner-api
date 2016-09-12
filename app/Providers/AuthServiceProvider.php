<?php
/**
 * Created by PhpStorm.
 * User: aholsinger
 * Date: 9/10/16
 * Time: 1:36 PM
 */

namespace Mealz\Providers;


use Illuminate\Support\Facades\Gate;
use Mealz\Models\User;

class AuthServiceProvider extends \Illuminate\Support\ServiceProvider {


    public function boot() {
        Gate::define(User::LIST_PERMISSION, function($user) {
           return $user->is_admin;
        });

        Gate::define(User::DELETE_PERMISSION, function($user) {
            if ($user->id == app('request')->route("users")) {
                return true;
            }
            return $user->is_admin;
        });
    }

    public function register() {

    }
}