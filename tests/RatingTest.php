<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RatingTest extends TestCase
{

    use DatabaseTransactions;


    protected $rating_json =
    ['data' =>
        [
            'id',
            'user',
            'rate',
            'description',
            'created_at'
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

    public function test_user_can_create_rating(){
        $rating = factory('App\Rating')->make();
        $response = $this->post("ratings", $rating->toArray(), $this->get_header());
        // dd($this->response->getContent());
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->rating_json);
    }

    public function test_if_rating_changes_reservation_status(){
        $rating = factory('App\Rating')->make();
        $response = $this->post("ratings", $rating->toArray(), $this->get_header());
        // dd($this->response->getContent());
        $this->seeInDatabase('reservations', ['id'=> $rating->reservation_id , 'status' => 'concluded']);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->rating_json);

    }

    public function test_if_rating_create_bill(){
        $rating = factory('App\Rating')->make();
        $response = $this->post("ratings", $rating->toArray(), $this->get_header());
        // dd($this->response->getContent());
        $this->seeInDatabase('bills', ['reservation_id'=> $rating->reservation_id]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure($this->rating_json);
    }

    public function test_user_can_get_all_ratings_data(){
        $user = factory('App\User')->create();

        $response = $this->post("login", [
            'email' => $user->email,
            'password' => '123'
        ])->response->getContent();
        $json = json_decode($response);
        $pet = factory('App\Pet')->create(['user_id' => $user->id]);
        $reservation = factory('App\Reservation')->create(['pet_id' => $pet->id]);
        $rating = factory('App\Rating')->create(['reservation_id' => $reservation->id]);
        $this->get('/ratings/', ['Authorization' => "Bearer $json->token"]);
        // dd($this->response->getContent());
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'id',
                    'user',
                    'rate',
                    'description',
                    'created_at'
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
}
