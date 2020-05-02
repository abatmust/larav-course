<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = collect(['travel', 'sciences', 'games', 'cars', 'books', 'news', 'training']);

        $tags->each(function($tag){
            $myTag = new Tag();
            $myTag->name = $tag;
            $myTag->save();
        });
    }
}
