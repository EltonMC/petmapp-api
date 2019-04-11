<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function user_can_authenticate(){
        $user = factory('App\User')->create();
        $this->post("login", [
            'email' => $user->email,
            'password' => '123'
        ]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['token']
        );
    }

    /** @test */
    public function user_can_not_authenticate(){
        $user = factory('App\User')->create();
        $this->post("login", [
            'email' => $user->email,
            'password' => '1234'
        ]);
        $this->seeStatusCode(400);
        $this->seeJsonEquals([
            'error'=> 'Email or password is wrong.'
        ]);
    }

    /** @test */
    public function user_authenticate_can_get_resource(){
        $user = factory('App\User')->create();
        $phone = factory('App\Phone')->create(['user_id'=> $user->id]);
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123'
        ])->response->getContent();
        $json = json_decode($response);
        $this->get('/users/'.$user->id, ['Authorization' => "Bearer $json->token"]);
        $this->seeStatusCode(200);
    }

    /** @test */
    public function user_authenticate_can_not_get_resource(){
        $user = factory('App\User')->create();
        $phone = factory('App\Phone')->create(['user_id'=> $user->id]);
        $response = $this->post("auth/login", [
            'email' => $user->email,
            'password' => '12345'
        ])->response->getContent();
        $json = json_decode($response);
        $this->get('/users/'.$user->id, ['Authorization' => "Bearer abcde"]);
        $this->seeStatusCode(400);
    }
}
