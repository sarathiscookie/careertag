<?php

use Illuminate\Database\Seeder;
use App\User;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach(range(1,10) as $index){
            User::insert([
                'firstname' => $faker->name,
                'lastname' => $faker->lastName,
                'city' => $faker->city,
                'email' => $faker->email,
                'password' => bcrypt('123456'),
                'phone' => $faker->phoneNumber,
                'created_at' => '2015-09-29',
            ]);
        }
    }
}
