<?php

namespace Mealz\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Mealz\Http\Requests;
use Mealz\Http\Controllers\Controller;
use Mealz\Http\Requests\UserEditRequest;
use Mealz\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows(User::LIST_PERMISSION)) {
            abort(403);
        }

        return User::paginate();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserEditRequest $request)
    {
       $user = User::create($request->all());
       return $this->createSuccess($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id)->toJson();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, $id)
    {
        User::find($id)->fill($request->all())->save();
        return $this->updateSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user == null) {
            abort(404);
        }

        if (Gate::allows(User::DELETE_PERMISSION)) {
            $user->delete();
            return $this->updateSuccess();
        }
        abort(403);
    }
}
