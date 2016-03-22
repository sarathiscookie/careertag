<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Tagcategory;
use App\Tag;

class TagcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagcategories = [
            ['title' => 'KEYSKILLS'],
            ['title' => 'INTEREST'],
            ['title' => 'PROFESSION'],
        ];

        Tagcategory::insert($tagcategories);
    }
}
