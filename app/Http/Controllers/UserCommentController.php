<?php

namespace App\Http\Controllers;

use App\Http\Requests\commentToStore;
use App\User;

class UserCommentController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store']);
    }
    
    public function store(commentToStore $request, User $user){

        $user->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);
        
        return redirect()->back()->withStatus('comment was created');
    }
}
