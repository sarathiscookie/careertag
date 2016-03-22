<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

        $this->call(TagcategoriesSeeder::class);
        $this->call(TaglanguageDataSeeder::class);
        $this->call(UserExperiencesSeeder::class);
        $this->call(KeyskillsSeeder::class);
        $this->call(InterestSeeder::class);
        $this->call(ProfessionSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(GraduationSeeder::class);
        $this->call(AbilitytestsSeeder::class);

        Model::reguard();
    }
}
