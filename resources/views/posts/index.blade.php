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
            @if($post->created_at->diffInHours() < 1)
               
               <x-badge type="success">New</x-badge>
            @else

            <x-badge type="info">Old</x-badge>
            @endif


                {{-- <img src="http://localhost:8000/storage/{{$post->image->path ?? null}}" class="img-fluid rounded" alt=""> --}}
                {{-- <img src="{{ asset($post->image->path ?? null)}}" class="img-fluid rounded" alt=""> --}}
                @if ($post->image)
                    <img src="{{ $post->image->url() }}" class="img-fluid rounded mt-4" alt=""> 
                @endif

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
                    <x-updated :date="$post->updated_at" :name="$post->user->name" :user-id="$post->user->id">Updated</x-updated>
                    <x-updated :date="$post->created_at"></x-updated>
                    
                    <x-tags :tags="$post->tags"></x-tags>
               
                    @if($post->comments_count)
                        <p>{{ $post->comments_count }} comments</p>
                    @else
                        <p>No comments yet!</p>
                    @endif
                    @auth
                    @can('update', $post)
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                        class="btn btn-info btn-sm m-1">
                        Edit
                    </a>
                    @endcan
                @if (!$post->deleted_at)
                @cannot('delete', $post)
                <x-badge type="danger">You can't delete this post</x-badge>
                   
                @endcannot
                @can('delete', $post)
                    <form method="POST" class="fm-inline"
                        action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                        @csrf
                        @method('DELETE')
        
                        <input type="submit" value="Delete!" class="btn btn-dark btn-sm m-1"/>
                    </form>
                @endcan
                @else
                    @can('restore', $post)
                    <form method="POST" class="fm-inline"
                        action="{{ url('/posts/'.$post->id.'/restore') }}">
                        @csrf
                        @method('PATCH')
        
                        <input type="submit" value="restore" class="btn btn-success btn-sm m-1"/>
                    </form>
                    @endcan
                    @can('forcedelete', $post)
                    <form method="POST" class="fm-inline"
                        action="{{ url('/posts/'.$post->id.'/forcedelete') }}">
                        @csrf
                        @method('DELETE')
        
                        <input type="submit" value="Force delete" class="btn btn-danger btn-sm m-1"/>
                    </form>
                    @endcan
                @endif
                @endauth
                </p>
            @empty
                <p>No blog posts yet!</p>
            @endforelse
       
    </div>
    <div class="col-4">
        
        
        @include('posts.sidebar')
        
        
    </div>
</div>
@endsection('content')
