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
        $users = App\User::all();
        factory(App\Comment::class,100)->make()->each(function($comment) use ($posts, $users){
            $comment->post_id = $posts->random()->id;
            $comment->user_id = $users->random()->id;

            $comment->save();
        });
    }
}
