<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentToStore;
use App\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function store(commentToStore $request, Post $post){

        $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);
        
        return redirect()->back();
    }
}
