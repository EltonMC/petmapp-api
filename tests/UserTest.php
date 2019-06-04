<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    use DatabaseTransactions;


    protected $user_json =
    ['data' =>
        [
            'id',
            'email',
            'name',
            'gender',
            'type',
            'photo',
            'created_at',
            'updated_at',
            'address',
            'phone',
            'notify_sms',
            'notify_email',
            'notify_push'
        ]
    ];

    private function create_user(){
        $user = factory('App\User')->create();
        factory('App\Phone')->create(['user_id'=> $user->id]);
        factory('App\Address')->create(['user_id'=> $user->id]);
        return $user;
    }


    public function test_can_create_a_user(){
        $user = [
            "name" => "Fabiola Herzog III",
            "password" => "123qwerty",
            "email" => "mcglynn.jameson@gmail.com",
            "gender" => "male",
            "type" => "petshop",
            "phone" => "123123123",
            "photo" => "https://lorempixel.com/640/480/?77198",
            "address" => factory('App\Address')->make()->toArray()
        ];
        $response = $this->post("users", $user, []);
        // dd($this->response->getContent());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);
    }

    public function test_can_not_create_a_user_because_email_exist(){
        $user = factory('App\User')->create();
        factory('App\Phone')->create(['user_id'=> $user->id]);
        factory('App\Address')->create(['user_id'=> $user->id]);
        $user = $user->toArray();
        $user['password'] = '123qwery';
        $response = $this->post("users", $user, []);
        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'email',
         ]);
    }

    public function test_can_get_user_info(){
        $user = $this->create_user();
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();
        $json = json_decode($response);
        $this->get('/users', ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);

    }

    public function test_can_update_user_personal_info(){
        $user = $this->create_user();
        $user->name = 'test';
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();

        $json = json_decode($response);
        $this->put('/users/'.$user->id, $user->toArray(), ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);
        $this->seeJson([
            'name' => 'test'
         ]);

    }

    public function test_can_update_user_phone(){
        $user = $this->create_user();
        $user->phone = '0000000000';
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();
        $json = json_decode($response);
        $this->put('/users/'.$user->id, $user->toArray(), ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);
        $this->seeJson([
            'phone' => '0000000000',
         ]);
    }

    public function test_can_update_user_location(){
        $user = $this->create_user();
        $user->address = [
            'street' => '123'
        ];
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();
        $json = json_decode($response);
        $this->put('/users/'.$user->id, $user->toArray(), ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);
        $this->seeJson([
            'street' => '123',
         ]);
    }

    public function test_can_not_update_user_location_id(){
        $user = $this->create_user();
        $user->address = [
            'user_id' => '123'
        ];
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();
        $json = json_decode($response);
        $this->put('/users/'.$user->id, $user->toArray(), ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);
        $this->seeJson([
            'user_id' => $user->id,
         ]);
    }

    public function test_can_not_update_user_id(){
        $user = $this->create_user();
        $old_id = $user->id;
        $user->id = '123';
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();
        $json = json_decode($response);
        $this->put('/users/'.$old_id, $user->toArray(), ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->user_json);
        $this->seeJson([
            'id' => $old_id,
         ]);
    }
}
