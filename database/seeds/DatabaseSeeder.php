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
        Model::unguard();
        
        /**
         * CREATE FAKE CLIENT
         */
        $user = factory(App\User::class)->create([
            'email' => 'client@petmapp.com.br',
            'type' => 'client'
        ]);
        factory(App\Address::class)->create(['user_id' => $user->id]);  
        factory(App\Phone::class)->create(['user_id' => $user->id]);  
        factory(App\Pet::class, 3)->create(['user_id' => $user->id]);  
        /**
         * END
         */

        /**
         * CREATE FAKE PETSHOP ATALAIA RAÇÕES
         */
        $user = factory(App\User::class)->create([
            'email' => 'petshop@petmapp.com.br',
            'type' => 'petshop'
        ]);
        factory(App\Address::class)->create(['user_id' => $user->id]);  
        factory(App\Phone::class)->create(['user_id' => $user->id]);  
        $petshop = factory(App\Petshop::class)->create([
            'user_id' => $user->id,
            'name' => 'Atalaia Rações',
            'schedule' => 'Segunda a sexta: 8h às 18h <br/> Sábado: 8h às 12h',
            'num_services' => 89,
            'max_discount' => 30,
            'rating_average' => 4.5
        ]);  
        factory(App\PetshopImage::class, 4)->create(['petshop_id' => $petshop->id]);  
        $service = factory(App\Service::class)->create([
            'petshop_id' => $petshop->id,
            'type' => 'shower',
            'status' => true,
            'max_discount' => 30,
            'price' => 90.00
        ]);  
        factory(App\Turn::class)->create([
            'service_id' => $service->id,
            'day' => 'mon',
            'time_start' => '08:00:00',
            'time_end' => '18:00:00',
            'status' => true,
        ]);
        factory(App\Turn::class)->create([
            'service_id' => $service->id,
            'day' => 'tue',
            'time_start' => '08:00:00',
            'time_end' => '18:00:00',
            'status' => true,
        ]);
        factory(App\Turn::class)->create([
            'service_id' => $service->id,
            'day' => 'wed',
            'time_start' => '08:00:00',
            'time_end' => '18:00:00',
            'status' => true,
        ]);
        factory(App\Turn::class)->create([
            'service_id' => $service->id,
            'day' => 'thu',
            'time_start' => '08:00:00',
            'time_end' => '18:00:00',
            'status' => true,
        ]);
        factory(App\Turn::class)->create([
            'service_id' => $service->id,
            'day' => 'fri',
            'time_start' => '08:00:00',
            'time_end' => '18:00:00',
            'status' => true,
        ]);
        factory(App\Turn::class)->create([
            'service_id' => $service->id,
            'day' => 'sat',
            'time_start' => '08:00:00',
            'time_end' => '18:00:00',
            'status' => true,
        ]);
        /**
         * END
         */

        Model::reguard();
    }
}
