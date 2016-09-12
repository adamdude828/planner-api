<?php
/**
 * Created by PhpStorm.
 * User: aholsinger
 * Date: 9/10/16
 * Time: 2:32 PM
 */

namespace Mealz\Http\Requests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Mealz\Models\User;

class UserEditRequest extends Request {

    public function rules() {
        $this_user = $this->route("users");
        $fields =  [
            'email'=>'required|email|unique:users,email,'.$this_user,
        ];
        if ($this->isMethod("POST")) {
            $fields = array_merge($fields, [
                'password'=>'required'
            ]);
        }
        return $fields;
    }

    public function authorize() {
        if ($this->isMethod("POST")) {
            return true; // anyone can create a user
        }

        return $this->route("users") == Auth::id(); //user is trying to update something about its self so its ok
    }
}