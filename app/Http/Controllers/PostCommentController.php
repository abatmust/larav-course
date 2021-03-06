<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentToStore;
use App\Post;

class PostCommentController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store']);
    }
    public function store(commentToStore $request, Post $post){

        $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        return redirect()->back();
    }



    
}
