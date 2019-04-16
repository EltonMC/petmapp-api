<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TurnTest extends TestCase
{

    use DatabaseTransactions;


    protected $turn_json =
    ['data' =>
        [
            'id',
            'service_id',
            'day',
            'time_start',
            'time_end',
            'max_reservation',
            'percent_discount',
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


    public function test_can_create_a_turn(){
        $turn = factory('App\Turn')->make();
        $response = $this->post("turns", $turn->toArray(), $this->get_header());
        // dd($this->response->getContent());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->turn_json);
    }

    public function test_can_get_turn_info(){
        $turn = factory('App\Turn')->create();
        $this->get('/turns/'.$turn->service_id, $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'service_id',
                    'day',
                    'time_start',
                    'time_end',
                    'max_reservation',
                    'percent_discount',
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
        ]);        }

    public function test_can_update_turn_name(){
        $turn = factory('App\Turn')->create();
        $turn->status = false;
        $this->put('/turns/'.$turn->id, $turn->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->turn_json);
        $this->seeJson([
            'status' => false
         ]);
    }

}
