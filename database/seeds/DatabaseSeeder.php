<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        factory(App\Product::class, 10)->create();
        Model::unguard();
        // Register the user seeder
        // factory(App\User::class, 10)->create();
        factory(App\Rating::class, 10)->create();
        factory(App\Turn::class, 10)->create();

        Model::reguard();
    }
}
