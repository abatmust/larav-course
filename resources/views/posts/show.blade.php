@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-8">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->content }}</p>
    
        <x-tags :tags="$post->tags"></x-tags>

        <p>Added {{ $post->created_at->diffForHumans() }}</p>

        @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5 )
        <strong>New!</strong>
        @endif

        @include('comments.form')
        
        <h4>Comments</h4>

        @forelse($post->comments as $comment)
            <p>
                {{ $comment->content }}
            </p>
            <p class="text-muted">
                <x-updated :date="$comment->updated_at" :name="$comment->user->name"></x-updated>
            </p>
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.sidebar')
    </div>
</div>
    
@endsection('content')