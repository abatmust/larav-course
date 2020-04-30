<?php

use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = App\Post::all();
        factory(App\Comment::class,100)->make()->each(function($comment) use ($posts){
            $comment->post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
