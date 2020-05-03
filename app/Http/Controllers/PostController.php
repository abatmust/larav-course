<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Requests\StorePost;
use App\Image;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
       
        return view(
            'posts.index', 
                [
                    
                'posts' => Post::withCommentsCtUserTags()->get()
        
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
            return Post::with(['comments', 'tags', 'comments.user'])->findOrFail($id);
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
        $post = Post::create($validatedData);
        
        $hasFile = $request->hasFile('picture');
        //dump($hasFile);
        if($hasFile){
            $file = $request->file('picture');
            $path = $file->store('posts');
            $image = new Image(['path' => $path]);
            $post->image()->save($image);
            //dump($file);
            //dump($file->getClientMimeType());
            // dump($file->getClientOriginalExtension());
            // dump($file->getClientOriginalName());

            // dump($file->store('logos'));
            // dump(Storage::putFile('thumb', $file));
            // dump(Storage::disk('public')->putFile('thumbStor', $file));
            // $name1 = $file->storeAs('mylogos', random_int(1,100) . '.' . $file->guessExtension());
            // $name2 = Storage::disk('local')->putFileAs('mylogos', $file, random_int(1,100) . '.' . $file->guessExtension());
            // dump(Storage::url($name1));
            // dump(Storage::disk('local')->url($name2));
        }

        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $post->id]);
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

        
        $hasFile = $request->hasFile('picture');
        if($hasFile){
            $file = $request->file('picture');
            $path = $file->store('posts');
            if($post->image){
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }
            else
            {

                $post->image()->save(Image::create(['path'=> $path]));
                
            }
            $image = new Image(['path' => $path]);
            $post->image()->save($image);
            
        }

        
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
