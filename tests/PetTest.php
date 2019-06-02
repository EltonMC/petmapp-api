<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PetTest extends TestCase
{

    use DatabaseTransactions;


    protected $pet_json =
    ['data' =>
        [
            'id',
            'user_id',
            'name',
            'birthday',
            'type',
            'breed',
            'gender',
            'temper',
            'castrated',
            'coat',
            'observation',
        ]
    ];

    private function get_header(){
        $user = factory('App\User')->create();

        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123qwerty'
        ])->response->getContent();
        $json = json_decode($response);

        return ['Authorization' => "Bearer $json->token"];
    }


    public function test_can_create_a_pet(){
        $pet = factory('App\Pet')->make();
        $response = $this->post("pets", $pet->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->pet_json);
    }

    public function test_can_view_all_pets_created_for_user(){
        $pet = factory('App\Pet')->create();
        $response = $this->get("pets", $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'user_id',
                    'name',
                    'birthday',
                    'type',
                    'breed',
                    'gender',
                    'temper',
                    'castrated',
                    'coat',
                    'observation',
                ]
            ],
            'meta' => [
                '*' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ]
            ]
        ]);
    }

    public function test_can_get_pet_info(){
        $pet = factory('App\Pet')->create();
        $this->get('/pets/'.$pet->id, $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->pet_json);

    }

    public function test_can_update_pet_name(){
        $pet = factory('App\Pet')->create();
        $pet->name = 'test';
        $this->put('/pets/'.$pet->id, $pet->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->pet_json);
        $this->seeJson([
            'name' => 'test'
         ]);
    }

    public function test_can_delete_pet(){
        $pet = factory('App\Pet')->create();
        $this->delete("pets/".$pet->id, [], $this->get_header());
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
                'status',
                'message'
        ]);
    }
}
