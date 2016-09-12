<?php
/**

 * Created by PhpStorm.
 * User: aholsinger
 * Date: 9/10/16
 * Time: 1:48 PM
 */

namespace MealzTest\Http\Controllers;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\URL;
use Mealz\Models\User;

class UserControllerTest extends \TestCase  {

    use DatabaseTransactions;

    public function test403OnGetUsersNotAuthorized() {
        $user = factory(User::class, 5)->create();
        $this->actingAs($user[1])->get("/users")
                    ->assertResponseStatus(403);
    }

    public function testGoodResponseWhenAuthorized() {
        $users = factory(User::class, 5)->create();

        //promote a user to admin
        $adminUser = $users[0];
        $adminUser->is_admin = 1;
        $adminUser->save();

        $result = $this->actingAs($adminUser)->get("/users");
        $json = json_decode($result->response->getContent(), true);
        foreach($json['data'] as $user) {
            $this->seeInDatabase('users', $user);
        }
    }

    public function testUserValiationWithoutPassword() {
        /** @var User $user */
        $user = factory(User::class)->make();
        //password is hidden so request will fail
        $this->json("POST", "/users", $user->toArray())
                    ->assertResponseStatus(422);
    }


    public function testUserInsertSuccess() {
        /** @var User $user */
        $user = factory(User::class)->make();
        $userData = $user->toArray();
        $userData['password'] = 'password';
        $resourceUrl = $this->json("POST", "/users", $userData)->response->headers->get("Location");

        $this->seeInDatabase('users', $user->toArray());
        $userFromDb = User::where('email', $user->email)->first();
        $this->assertEquals(URL::to("/users/{$userFromDb->id}"), $resourceUrl);

        $this->seeInDatabase('users', $user->toArray());

        $this->json("POST", "/users", $userData)
                ->assertResponseStatus(422);
    }

    public function testUserShow() {
        $user = factory(User::class)->create();
        $returned = json_decode($this->get("/users/{$user->id}")->response->getContent(), true);
        $this->assertEquals($user->email, $returned['email']);
    }

    public function testUserUpdateFailNotLoggedIn() {
        //user has to provide credentials to update his record
        $user = factory(User::class)->create();
        $userData = $user->toArray();
        $this->put("/users/{$user->id}", $userData)
                        ->assertResponseStatus(403);
    }

    public function testUserUpdateSuccess() {
        //user has to provide credentials to update his record
        $user = factory(User::class)->create();
        $userData = $user->toArray();
        $userData['email'] = 'changed@changed.com';
        $this->actingAs($user)->json("PUT", "/users/{$user->id}", $userData)
                    ->assertResponseStatus(204);

        $exists = User::where("email", "changed@changed.com")->count();
        $this->assertGreaterThan(0, $exists);
    }

    public function userTestEmailUnique() {
        $users = factory(User::class, 2)->create();
        $userToChange = $users[0]->toArray();
        $userToChange['email'] = $users[1]->email;

        $this->actingAs($users[0])->json("PUT", "/users/".$users[0]->id, $userToChange)
                ->assertResponseStatus(422);
    }

    public function testDeleteUser() {
        $user = factory(User::class)->create();
        $this->actingAs($user)->json("DELETE", "/users/".$user->id);
        $this->dontSeeInDatabase('users', $user->toArray());
    }

    public function testDeleteByOtherUser() {
        $users = factory(User::class, 2)->create();
        $this->actingAs($users[0])->json("DELETE", "/users/".$users[1]->id)
                ->assertResponseStatus(403);
    }

}