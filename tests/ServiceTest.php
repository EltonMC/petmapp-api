<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ServiceTest extends TestCase
{

    use DatabaseTransactions;


    protected $service_json =
    ['data' =>
        [
            'id',
            'petshop_id',
            'type',
            'status',
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


    public function test_can_create_a_service(){
        $service = factory('App\Service')->make();
        $response = $this->post("services", $service->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->service_json);
    }

    // public function test_can_get_service_info(){
    //     $service = factory('App\Service')->create();
    //     $this->get('/services/'.$service->id, $this->get_header());
    //     $this->seeStatusCode(200);
    //     $this->seeJsonStructure($this->service_json);
    // }

    public function test_can_get_petshop_services_info(){
        $service = factory('App\Service')->create();
        $this->get('/services/'.$service->petshop_id, $this->get_header());
        $this->seeStatusCode(200);
        // dd($this->response->getContent());
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'petshop_id',
                    'type',
                    'status',
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
    }


    public function test_can_update_service_name(){
        $service = factory('App\Service')->create();
        $service->status = false;
        $this->put('/services/'.$service->id, $service->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->service_json);
        $this->seeJson([
            'status' => false
         ]);
    }

}
