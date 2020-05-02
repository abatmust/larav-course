<?php

use App\Post;
use App\Tag;
use Illuminate\Database\Seeder;

class PostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagsCount = Tag::count();
        Post::all()->each(function($post) use ($tagsCount){
            $taken = random_int(1, $tagsCount);
            $tagsId = Tag::inRandomOrder()->take($taken)->get()->pluck('id');
            $post->tags()->sync($tagsId);
        });
    }
}
