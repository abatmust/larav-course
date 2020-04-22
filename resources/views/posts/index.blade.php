@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
        
        
        <div>
        <h4>{{$posts->count()}} post(s)</h4>
        </div>
        <div class="badge badge-success p-2">{{App\Comment::count()}}</div>
            @forelse ($posts as $post)
                <p>
                    <h3>
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            @if ($post->trashed())
                            <del>
                                 {{ $post->title }}
                                </del>
                            @else
                                {{ $post->title }}
                            
                            @endif
                        
                        </a>
                    </h3>

                <p class="text-muted">{{$post->updated_at->diffForHumans()}}, by {{$post->user->name}}</p>
        
                    @if($post->comments_count)
                        <p>{{ $post->comments_count }} comments</p>
                    @else
                        <p>No comments yet!</p>
                    @endif
                    @can('update', $post)
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                        class="btn btn-outline-dark m-1">
                        Edit
                    </a>
                    @endcan
                @if (!$post->deleted_at)
                @cannot('delete', $post)
                    <span class="badge badge-danger">You can't delete this post</span>
                @endcannot
                @can('delete', $post)
                    <form method="POST" class="fm-inline"
                        action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                        @csrf
                        @method('DELETE')
        
                        <input type="submit" value="Delete!" class="btn btn-outline-danger m-1"/>
                    </form>
                @endcan
                @else
                    @can('restore', $post)
                    <form method="POST" class="fm-inline"
                        action="{{ url('/posts/'.$post->id.'/restore') }}">
                        @csrf
                        @method('PATCH')
        
                        <input type="submit" value="restore" class="btn btn-outline-success m-1"/>
                    </form>
                    @endcan
                    @can('forcedelete', $post)
                    <form method="POST" class="fm-inline"
                        action="{{ url('/posts/'.$post->id.'/forcedelete') }}">
                        @csrf
                        @method('DELETE')
        
                        <input type="submit" value="Force delete" class="btn btn-outline-danger m-1"/>
                    </form>
                    @endcan
                @endif
                </p>
            @empty
                <p>No blog posts yet!</p>
            @endforelse
       
    </div>
    <div class="col-4">
        <div class="card">
           
            <div class="card-body">
                <h4 class="card-title">MOST COMMENTED POSTS</h4>
                
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostCommented as $post)
                    
                <li class="list-group-item">
                    <a href="">{{$post->title}}</a>
                    <p> <span class="badge badge-success">{{ $post->comments_count }}</span> comments</p>
                </li>
                @endforeach
                
            </ul>
        </div>
        
        <div class="card mt-3">
           
            <div class="card-body">
                <h4 class="card-title">MOST ACTIVE USERS</h4>
                
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostActiveUsers as $user)
                    
                <li class="list-group-item">
                    
                    <p> <span class="badge badge-success">{{ $user->posts_count }}</span> {{$user->name}}</p>
                </li>
                @endforeach
                
            </ul>
        </div>
        <div class="card mt-3">
           
            <div class="card-body">
                <h4 class="card-title">LAST MONTH MOST ACTIVE USERS</h4>
                
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($lastMonthMostActiveUsers as $user)
                    
                <li class="list-group-item">
                    
                    <p> <span class="badge badge-success">{{ $user->posts_count }}</span> {{$user->name}}</p>
                </li>
                @endforeach
                
            </ul>
        </div>
    </div>
</div>
@endsection('content')
