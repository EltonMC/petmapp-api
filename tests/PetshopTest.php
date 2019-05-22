<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PetshopTest extends TestCase
{

    use DatabaseTransactions;


    protected $petshop_json =
    ['data' =>
        [
            'id',
            'user_id',
            'name',
            'description',
            'phone',
            'address',
            'images',
            'created_at',
            'updated_at',
        ]
    ];

    private function get_header(){
        $user = factory('App\User')->create();
        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123'
        ])->response->getContent();
        $json = json_decode($response);
        return ['Authorization' => "Bearer $json->token"];
    }

    public function test_can_create_a_petshop(){
        $petshop = factory('App\Petshop')->make();
        $response = $this->post("petshops", $petshop->toArray(), $this->get_header());
        // dd($this->response->getContent());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->petshop_json);
    }

    public function test_can_get_all_petshops(){
        $petshop = factory('App\Petshop')->create();
        $this->get('/petshops', $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'user_id',
                    'name',
                    'description',
                    'phone',
                    'address',
                    'images',
                    'created_at',
                    'updated_at',
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
        // $this->seeJsonStructure($this->petshop_json);
    }

    public function test_can_get_petshop_info(){
        $petshop = factory('App\Petshop')->create();
        $this->get('/petshops/'.$petshop->id, $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->petshop_json);

    }

    public function test_can_update_petshop_name(){
        $petshop = factory('App\Petshop')->create();
        $petshop->name = 'test';
        $this->put('/petshops/'.$petshop->id, $petshop->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->petshop_json);
        $this->seeJson([
            'name' => 'test'
         ]);
    }

}
