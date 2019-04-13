<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class FeedbackTest extends TestCase
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
            'password' => '123'
        ])->response->getContent();
        $json = json_decode($response);

        return ['Authorization' => "Bearer $json->token"];
    }

    // public function test_user_can_create_feedback(){

    // }

    // public function test_if_feeback_changes_reservation_status(){

    // }

    // public function test_if_feeback_create_bill(){

    // }

    // public function test_user_can_get_all_feedbacks_data(){

    // }

    // public function test_user_can_get_feedback_data(){

    // }


}
