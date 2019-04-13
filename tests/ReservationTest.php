<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ReservationTest extends TestCase
{

    use DatabaseTransactions;


    protected $reservation_json =
    ['data' =>
        [
            'id',
            'pet',
            'petshop',
            'service',
            'turn',
            'status',
            'reservation_day',
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

    public function test_user_can_create_reservation(){
        $reservation = factory('App\Reservation')->make();
        $response = $this->post("reservations", $reservation->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->reservation_json);
    }

    public function test_user_can_not_create_reservation_because_out_limit(){
        $turn = factory('App\Turn')->create(['max_reservation' => 0]);
        $reservation = factory('App\Reservation')->make(['turn_id' => $turn->id]);
        $response = $this->post("reservations", $reservation->toArray(), $this->get_header());
        // dd($this->response->getContent());
        $this->seeStatusCode(400);
        $this->seeJson([
            'message' => 'Failed to create reservation, limit exceeded'
         ]);
    }

    public function test_user_can_not_create_reservation_because_turn_id_not_exist(){
        $reservation = factory('App\Reservation')->make(['turn_id' => '1234']);
        $response = $this->post("reservations", $reservation->toArray(), $this->get_header());
        $this->seeStatusCode(400);
        $this->seeJson([
            'message' => 'Failed to create reservation, turn not exit!'
         ]);
    }


    public function test_user_can_not_create_reservation_because_date_is_different_to_turn(){
        $reservation = factory('App\Reservation')->make(['pet_id' => '1234']);
        $response = $this->post("reservations", $reservation->toArray(), $this->get_header());
        $this->seeStatusCode(400);
        $this->seeJson([
            'message' => 'Failed to create reservation, pet not exit!'
         ]);
    }

    public function test_user_can_get_all_reservations_data(){
        $user = factory('App\User')->create();

        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123'
        ])->response->getContent();
        $json = json_decode($response);
        $pet = factory('App\Pet')->create(['user_id' => $user->id]);
        $reservation = factory('App\Reservation')->create(['pet_id' => $pet->id]);
        $this->get('/reservations/', ['Authorization' => "Bearer $json->token"]);
        // dd($this->response->getContent());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'pet',
                    'petshop',
                    'service',
                    'turn',
                    'status',
                    'reservation_day',
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

    public function test_user_can_change_reservation_status(){
        $reservation = factory('App\Reservation')->create();
        $reservation->status = 'approved';
        $this->put('/reservations/'.$reservation->id, $reservation->toArray(), $this->get_header());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->reservation_json);
        $this->seeJson([
            'status' => 'approved'
         ]);
    }
}
