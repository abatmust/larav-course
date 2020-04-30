<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Requests\StorePost;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

// use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = Cache::remember('posts', now()->addSeconds(10), function(){
        //     Post::withCount('comments')->with('user')->get();
        // });
        $mostCommented = Cache::remember('mostCommented', now()->addSeconds(10), function(){
            return Post::withCount('comments')->with('user')->get();
        });
        $mostActiveUsers = Cache::remember('mostActiveUsers', now()->addSeconds(10), function(){
            return User::mostActiveUsers()->take(5)->get();
        });
        $lastMonthMostActiveUsers = Cache::remember('lastMonthMostActiveUsers', now()->addSeconds(10), function(){
            return User::lastMonthMostActiveUsers()->take(5)->get();
        });
        return view(
            'posts.index', 
            [
                'posts' => Post::withCount('comments')->with(['user', 'tags'])->get(),
                'mostCommented' => $mostCommented,
                'mostActiveUsers' =>  $mostActiveUsers ,
                'lastMonthMostActiveUsers' => $lastMonthMostActiveUsers,
                'tab'=>'list'
                ]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function archive()
    // {
     
    //     return view(
    //         'posts.index', 
    //         [
    //             'posts' => Post::onlyTrashed()->withCount('comments')->get(),
    //             'mostCommented' => Post::mostCommented()->take(5)->get(),
    //             'mostActiveUsers' => User::mostActiveUsers()->take(5)->get(),
    //             'lastMonthMostActiveUsers' => User::lastMonthMostActiveUsers()->take(5)->get(),
    //             'tab'=>'archive'
    //             ]
    //     );
    // }
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function all()
    // {
     
    //     return view(
    //         'posts.index', 
    //         [
    //             'posts' => Post::withTrashed()->withCount('comments')->get(),
    //             'mostCommented' => Post::mostCommented()->take(5)->get(),
    //             'mostActiveUsers' => User::mostActiveUsers()->take(5)->get(),
    //             'lastMonthMostActiveUsers' => User::lastMonthMostActiveUsers()->take(5)->get(),
    //              'tab'=>'all'
    //             ]
    //     );
    // }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $postShow = Cache::remember("post-show-{$id}", 120, function() use ($id){
            return Post::with('comments')->findOrFail($id);
        });
        return view('posts.show', [
            'post' => $postShow
        ]);
    }

    public function create()
    {
        //$this->authorize('create');
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $Post = Post::create($validatedData);
        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $Post->id]);
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = Post::findOrFail($id);
        // if (Gate::denies('post.update', $post)) {
        //     abort(403,'You are not supposed to be here!!');
        // }
        $this->authorize('update', $post);
        
        $validatedData = $request->validated();
        
        $post->fill($validatedData);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');
        
        return redirect()->route('posts.show', ['post' => $post->id]);
    }
    
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);
        $post->delete();

        // Post::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
    public function restore($id){
        $post = Post::onlyTrashed()->where('id', $id)->first();
        $post->restore();
        return redirect()->back();

    }
    public function forcedelete($id){
        $post = Post::onlyTrashed()->where('id', $id)->first();
        $post->forcedelete();
        return redirect()->back();

    }
}
